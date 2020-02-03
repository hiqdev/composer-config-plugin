<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use hiqdev\composer\config\configs\ConfigFactory;

/**
 * Builder assembles config files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Builder
{
    /**
     * @var string path to output assembled configs
     */
    protected $outputDir;

    /**
     * @var array collected variables
     */
    protected $vars = [];

    /**
     * @var array configurations
     */
    protected $configs = [];

    const OUTPUT_DIR_SUFFIX = '-output';

    public function __construct($outputDir = null)
    {
        $this->setOutputDir($outputDir);
    }

    public function createAlternative($name): Builder
    {
        $dir = $this->outputDir . DIRECTORY_SEPARATOR . $name;
        $alt = new static($dir);
        foreach (['aliases', 'packages'] as $key) {
            $alt->configs[$key] = $this->getConfig($key)->clone($alt);
        }

        return $alt;
    }

    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir
            ? static::buildAbsPath($this->getBaseDir(), $outputDir)
            : static::findOutputDir();
    }

    public function getBaseDir(): string
    {
        return dirname(__DIR__, 4);
    }

    public function getOutputDir(): string
    {
        return $this->outputDir;
    }

    public static function rebuild($outputDir = null)
    {
        $builder = new self($outputDir);
        $files = $builder->getConfig('__files')->load();
        $builder->buildUserConfigs($files->getValues());
    }

    public function rebuildUserConfigs()
    {
        $this->getConfig('__files')->load();
    }

    /**
     * Returns default output dir.
     * @param string $baseDir path to project base dir
     * @return string
     */
    public static function findOutputDir(string $baseDir = null): string
    {
        $baseDir = $baseDir ?: static::findBaseDir();
        $path = "$baseDir/composer.json";
        $data = json_decode(file_get_contents($path), true);
        $dir = $data['extra'][Package::EXTRA_OUTPUT_DIR_OPTION_NAME] ?? null;

        return $dir ? static::buildAbsPath($baseDir, $dir) : static::defaultOutputDir($baseDir);
    }

    public static function findBaseDir(): string
    {
        return dirname(__DIR__, 4);
    }

    /**
     * Returns default output dir.
     * @param string $vendor path to vendor dir
     * @return string
     */
    public static function defaultOutputDir($baseDir = null): string
    {
        if ($baseDir) {
            $dir = $baseDir . '/vendor/hiqdev/' . basename(dirname(__DIR__));
        } else {
            $dir = \dirname(__DIR__);
        }

        return $dir . static::OUTPUT_DIR_SUFFIX;
    }

    /**
     * Returns full path to assembled config file.
     * @param string $filename name of config
     * @param string $baseDir path to base dir
     * @return string absolute path
     */
    public static function path($filename, $baseDir = null)
    {
        return static::buildAbsPath(static::findOutputDir($baseDir), $filename . '.php');
    }

    public static function buildAbsPath(string $dir, string $file): string
    {
        return strncmp($file, DIRECTORY_SEPARATOR, 1) === 0 ? $file : $dir . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Builds all (user and system) configs by given files list.
     * @param null|array $files files to process: config name => list of files
     */
    public function buildAllConfigs(array $files)
    {
        $this->buildUserConfigs($files);
        $this->buildSystemConfigs($files);
    }

    /**
     * Builds configs by given files list.
     * @param null|array $files files to process: config name => list of files
     */
    public function buildUserConfigs(array $files): array
    {
        $resolver = new Resolver($files);
        $files = $resolver->get();
        foreach ($files as $name => $paths) {
            $this->getConfig($name)->load($paths)->build()->write();
        }

        return $files;
    }

    public function buildSystemConfigs(array $files)
    {
        $this->getConfig('__files')->setValues($files);
        foreach (['__rebuild', '__files', 'aliases', 'packages'] as $name) {
            $this->getConfig($name)->build()->write();
        }
    }

    public function getOutputPath($name)
    {
        return $this->outputDir . DIRECTORY_SEPARATOR . $name . '.php';
    }

    protected function createConfig($name)
    {
        $config = ConfigFactory::create($this, $name);
        $this->configs[$name] = $config;

        return $config;
    }

    public function getConfig(string $name)
    {
        if (!isset($this->configs[$name])) {
            $this->configs[$name] = $this->createConfig($name);
        }

        return $this->configs[$name];
    }

    public function getVar($name, $key)
    {
        $config = $this->configs[$name] ?? null;
        if (empty($config)) {
            return null;
        }

        return $config->getValues()[$key] ?? null;
    }

    public function getVars()
    {
        $vars = [];
        foreach ($this->configs as $name => $config) {
            $vars[$name] = $config->getValues();
        }

        return $vars;
    }

    public function mergeAliases(array $aliases)
    {
        $this->getConfig('aliases')->mergeValues($aliases);
    }

    public function setPackage(string $name, array $data)
    {
        $this->getConfig('packages')->setValue($name, $data);
    }
}
