<?php

/*
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\ComposerConfigPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
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
    const PACKAGE_TYPE = 'yii2-extension';
    const EXTRA_OPTION_NAME = 'config-plugin';
    const OUTPUT_PATH = 'hiqdev/config';
    const BASE_DIR_SAMPLE = '<base-dir>';
    const VENDOR_DIR_SAMPLE = '<base-dir>/vendor';

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
     * @var array assembled config data
     */
    protected $data = [
        'aliases' => [],
        'extensions' => [],
    ];

    /**
     * @var array raw collected data
     */
    protected $raw = [];

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

        /// scan packages
        foreach ($this->getPackages() as $package) {
            if ($package instanceof \Composer\Package\CompletePackageInterface) {
                $this->processPackage($package);
            }
        }
        $this->processPackage($this->composer->getPackage());

        $this->assembleParams();
        define('COMPOSER_CONFIG_PLUGIN_DIR', $this->getOutputDir());
        $this->assembleConfigs();
    }

    /**
     * Scans the given package and collects extensions data.
     * @param PackageInterface $package
     */
    public function processPackage(PackageInterface $package)
    {
        $extra = $package->getExtra();
        $files = isset($extra[self::EXTRA_OPTION_NAME]) ? $extra[self::EXTRA_OPTION_NAME] : null;
        if ($package->getType() !== self::PACKAGE_TYPE && is_null($files)) {
            return;
        }

        $extension = [
            'name' => $package->getPrettyName(),
            'version' => $package->getVersion(),
        ];
        if ($package->getVersion() === '9999999-dev') {
            $reference = $package->getSourceReference() ?: $package->getDistReference();
            if ($reference) {
                $extension['reference'] = $reference;
            }
        }

        $aliases = array_merge(
            $this->prepareAliases($package, 'psr-0'),
            $this->prepareAliases($package, 'psr-4')
        );

        if (isset($files['defines'])) {
            foreach ((array) $files['defines'] as $file) {
                $this->readConfigFile($package, $file);
            }
            unset($files['defines']);
        }

        if (isset($files['params'])) {
            foreach ((array) $files['params'] as $file) {
                $this->rawParams[] = $this->readConfigFile($package, $file);
            }
            unset($files['params']);
        }

        $this->raw[$package->getPrettyName()] = [
            'package' => $package,
            'extension' => $extension,
            'aliases' => $aliases,
            'files' => (array) $files,
        ];
    }

    public function assembleParams()
    {
        $this->assembleFile('params', $this->rawParams);
    }

    public function assembleConfigs()
    {
        $allAliases = [];
        $extensions = [];
        $rawConfigs = [
            'aliases' => [],
            'extensions' => [],
        ];

        foreach ($this->raw as $name => $info) {
            $rawConfigs['extensions'][] = [
                $name => $info['extension'],
            ];

            $aliases = $info['aliases'];
            $rawConfigs['aliases'][] = $aliases;

            foreach ($info['files'] as $name => $pathes) {
                foreach ((array) $pathes as $path) {
                    $rawConfigs[$name][] = $this->readConfigFile($info['package'], $path);
                }
            }
        }

        foreach ($rawConfigs as $name => $configs) {
            if (!in_array($name, ['params', 'aliases', 'extensions'], true)) {
                $configs[] = [
                    'params' => $this->data['params'],
                    'aliases' => $this->data['aliases'],
                ];
            }
            $this->assembleFile($name, $configs);
        }
    }

    protected function assembleFile($name, array $configs)
    {
        $this->data[$name] = call_user_func_array([Helper::class, 'mergeConfig'], $configs);
        $this->writeFile($name, (array) $this->data[$name]);
    }

    /**
     * Read extra config.
     * @param string $file
     * @return array
     */
    protected function readConfigFile(PackageInterface $package, $file)
    {
        $skippable = false;
        if (strncmp($file, '?', 1) === 0) {
            $skippable = true;
            $file = substr($file, 1);
        }
        $__path = $this->preparePath($package, $file);
        if (!file_exists($__path)) {
            if ($skippable) {
                return [];
            } else {
                $this->io->writeError('<error>Non existent extension config file</error> ' . $file . ' in ' . $package->getPrettyName());
                exit(1);
            }
        }
        extract($this->data);

        return (array) require $__path;
    }

    /**
     * Prepare aliases.
     *
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
            $path = $this->substitutePath($path, $this->getBaseDir(), self::BASE_DIR_SAMPLE);
            if ('psr-0' === $psr) {
                $path .= '/' . $name;
            }
            $aliases["@$name"] = $path;
        }

        return $aliases;
    }

    /**
     * Substitute path with alias if applicable.
     * @param string $path
     * @param string $dir
     * @param string $alias
     * @return string
     */
    public function substitutePath($path, $dir, $alias)
    {
        return (substr($path, 0, strlen($dir) + 1) === $dir . '/') ? $alias . substr($path, strlen($dir)) : $path;
    }

    /**
     * Builds path inside of a package.
     * @param PackageInterface $package
     * @param mixed $path can be absolute or relative
     * @return string absolute pathes will stay untouched
     */
    public function preparePath(PackageInterface $package, $path)
    {
        if (!$this->getFilesystem()->isAbsolutePath($path)) {
            $prefix = $package instanceof RootPackageInterface ? $this->getBaseDir() : $this->getVendorDir() . '/' . $package->getPrettyName();
            $path = $prefix . '/' . $path;
        }

        return $this->getFilesystem()->normalizePath($path);
    }

    /**
     * Get output dir.
     * @return string
     */
    public function getOutputDir()
    {
        return $this->getVendorDir() . DIRECTORY_SEPARATOR . static::OUTPUT_PATH;
    }

    /**
     * Build full path to write file for a given filename.
     * @param string $filename
     * @return string
     */
    public function buildOutputPath($filename)
    {
        return $this->getOutputDir() . DIRECTORY_SEPARATOR . $filename . '.php';
    }

    /**
     * Writes config file.
     * @param string $filename
     * @param array $data
     */
    protected function writeFile($filename, array $data)
    {
        $path = $this->buildOutputPath($filename);
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        $array = str_replace("'" . self::BASE_DIR_SAMPLE, '$baseDir . \'', Helper::exportVar($data));
        file_put_contents($path, "<?php\n\n\$baseDir = dirname(dirname(dirname(__DIR__)));\n\nreturn $array;\n");
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

    protected $plainList = [];
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
        #var_dump(implode("\n", $this->orderedList)); die();
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
        $this->iterateDependencies($package);
        if ($includingDev) {
            $this->iterateDependencies($package, true);
        }
        $name = $package->getPrettyName();
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
