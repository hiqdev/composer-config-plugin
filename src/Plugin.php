<?php

/*
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Package\CompletePackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;

/**
 * Plugin class.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    const YII2_PACKAGE_TYPE = 'yii2-extension';
    const EXTRA_OPTION_NAME = 'config-plugin';

    /**
     * @var PackageInterface[] the array of active composer packages
     */
    protected $packages;

    /**
     * @var string absolute path to the package base directory
     */
    protected $baseDir;

    /**
     * @var string absolute path to vendor directory
     */
    protected $vendorDir;

    /**
     * @var Filesystem utility
     */
    protected $filesystem;

    /**
     * @var array config name => list of files
     */
    protected $files = [
        'defines' => [],
        'params'  => [],
    ];

    protected $aliases = [];

    protected $extensions = [];

    /**
     * @var array array of not yet merged params
     */
    protected $rawParams = [];

    /**
     * @var Composer instance
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    public $io;

    /**
     * Initializes the plugin object with the passed $composer and $io.
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * Returns list of events the plugin is subscribed to.
     * @return array list of events
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_AUTOLOAD_DUMP => [
                ['onPostAutoloadDump', 0],
            ],
        ];
    }

    /**
     * This is the main function.
     * @param Event $event
     */
    public function onPostAutoloadDump(Event $event)
    {
        $this->io->writeError('<info>Assembling config files</info>');
        $this->scanPackages();

        $builder = new Builder($this->files);
        $builder->setAddition(['aliases' => $this->aliases]);
        $builder->setIo($this->io);
        $builder->saveFiles();
        $builder->writeConfig('aliases', $this->aliases);
        $builder->writeConfig('extensions', $this->extensions);
        $builder->buildConfigs();
    }

    protected function scanPackages()
    {
        foreach ($this->getPackages() as $package) {
            if ($package instanceof CompletePackageInterface) {
                $this->processPackage($package);
            }
        }
    }

    /**
     * Scans the given package and collects extensions data.
     * @param PackageInterface $package
     */
    protected function processPackage(CompletePackageInterface $package)
    {
        $extra = $package->getExtra();
        $files = isset($extra[self::EXTRA_OPTION_NAME]) ? $extra[self::EXTRA_OPTION_NAME] : null;

        if ($package->getType() !== self::YII2_PACKAGE_TYPE && is_null($files)) {
            return;
        }

        if (is_array($files)) {
            $this->processFiles($package, $files);
        }

        $aliases = array_merge(
            $this->prepareAliases($package, 'psr-0'),
            $this->prepareAliases($package, 'psr-4')
        );
        $this->aliases = array_merge($this->aliases, $aliases);

        $this->extensions[$package->getPrettyName()] = array_filter([
            'name' => $package->getPrettyName(),
            'version' => $package->getVersion(),
            'reference' => $package->getSourceReference() ?: $package->getDistReference(),
            'aliases' => $aliases,
        ]);
    }

    protected function processFiles(CompletePackageInterface $package, array $files)
    {
        foreach ($files as $name => $pathes) {
            foreach ((array) $pathes as $path) {
                if (!isset($this->files[$name])) {
                    $this->files[$name] = [];
                }
                array_push($this->files[$name], $this->preparePath($package, $path));
            }
        }
    }

    /**
     * Prepare aliases.
     * @param PackageInterface $package
     * @param string 'psr-0' or 'psr-4'
     * @return array
     */
    protected function prepareAliases(PackageInterface $package, $psr)
    {
        $autoload = $package->getAutoload();
        if (empty($autoload[$psr])) {
            return [];
        }

        $aliases = [];
        foreach ($autoload[$psr] as $name => $path) {
            if (is_array($path)) {
                // ignore psr-4 autoload specifications with multiple search paths
                // we can not convert them into aliases as they are ambiguous
                continue;
            }
            $name = str_replace('\\', '/', trim($name, '\\'));
            $path = $this->preparePath($package, $path);
            if ('psr-0' === $psr) {
                $path .= '/' . $name;
            }
            $aliases["@$name"] = $path;
        }

        return $aliases;
    }

    /**
     * Builds path inside of a package.
     * @param PackageInterface $package
     * @param mixed $path can be absolute or relative
     * @return string absolute pathes will stay untouched
     */
    public function preparePath(PackageInterface $package, $path)
    {
        $skippable = false;
        if (strncmp($path, '?', 1) === 0) {
            $skippable = true;
            $path = substr($path, 1);
        }
        if (!$this->getFilesystem()->isAbsolutePath($path)) {
            $prefix = $package instanceof RootPackageInterface
                ? $this->getBaseDir()
                : $this->getVendorDir() . '/' . $package->getPrettyName()
            ;
            $path = $prefix . '/' . $path;
        }

        return ($skippable ? '?' : '') . $this->getFilesystem()->normalizePath($path);
    }

    /**
     * Sets [[packages]].
     * @param PackageInterface[] $packages
     */
    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }

    /**
     * Gets [[packages]].
     * @return \Composer\Package\PackageInterface[]
     */
    public function getPackages()
    {
        if ($this->packages === null) {
            $this->packages = $this->findPackages();
        }

        return $this->packages;
    }

    /**
     * Plain list of all project dependencies (including nested) as provided by composer.
     * The list is unordered (chaotic, can be different after every update).
     */
    protected $plainList = [];

    /**
     * Ordered list of package. Order @see findPackages
     */
    protected $orderedList = [];

    /**
     * Returns ordered list of packages:
     * - listed earlier in the composer.json will get earlier in the list
     * - childs before parents.
     * @return \Composer\Package\PackageInterface[]
     */
    public function findPackages()
    {
        $root = $this->composer->getPackage();
        $this->plainList[$root->getPrettyName()] = $root;
        foreach ($this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages() as $package) {
            $this->plainList[$package->getPrettyName()] = $package;
        }
        $this->orderedList = [];
        $this->iteratePackage($root, true);

        if ($this->io->isVerbose())  {
            $indent = ' - ';
            $packages = $indent . implode("\n$indent", $this->orderedList);
            $this->io->writeError($packages);
        }
        $res = [];
        foreach ($this->orderedList as $name) {
            $res[] = $this->plainList[$name];
        }

        return $res;
    }

    /**
     * Iterates through package dependencies.
     * @param PackageInterface $package to iterate
     * @param bool $includingDev process development dependencies, defaults to not process
     */
    public function iteratePackage(PackageInterface $package, $includingDev = false)
    {
        $name = $package->getPrettyName();

        /// prevent infinite loop in case of circular dependencies
        static $processed = [];
        if (isset($processed[$name])) {
            return;
        } else {
            $processed[$name] = 1;
        }

        $this->iterateDependencies($package);
        if ($includingDev) {
            $this->iterateDependencies($package, true);
        }
        if (!isset($this->orderedList[$name])) {
            $this->orderedList[$name] = $name;
        }
    }

    /**
     * Iterates dependencies of the given package.
     * @param PackageInterface $package
     * @param bool $dev which dependencies to iterate: true - dev, default - general
     */
    public function iterateDependencies(PackageInterface $package, $dev = false)
    {
        $path = $this->preparePath($package, 'composer.json');
        if (file_exists($path)) {
            $conf = json_decode(file_get_contents($path), true);
            $what = $dev ? 'require-dev' : 'require';
            $deps = isset($conf[$what]) ? $conf[$what] : [];
        } else {
            $deps = $dev ? $package->getDevRequires() : $package->getRequires();
        }
        foreach (array_keys($deps) as $target) {
            if (isset($this->plainList[$target]) && !isset($this->orderedList[$target])) {
                $this->iteratePackage($this->plainList[$target]);
            }
        }
    }

    /**
     * Get absolute path to package base dir.
     * @return string
     */
    public function getBaseDir()
    {
        if ($this->baseDir === null) {
            $this->baseDir = dirname($this->getVendorDir());
        }

        return $this->baseDir;
    }

    /**
     * Get absolute path to composer vendor dir.
     * @return string
     */
    public function getVendorDir()
    {
        if ($this->vendorDir === null) {
            $dir = $this->composer->getConfig()->get('vendor-dir', '/');
            $this->vendorDir = $this->getFilesystem()->normalizePath($dir);
        }

        return $this->vendorDir;
    }

    /**
     * Getter for filesystem utility.
     * @return Filesystem
     */
    public function getFilesystem()
    {
        if ($this->filesystem === null) {
            $this->filesystem = new Filesystem();
        }

        return $this->filesystem;
    }
}
