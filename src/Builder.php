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

    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir ?: static::findOutputDir();
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
     * @param string $vendor path to vendor dir
     * @return string
     */
    public static function findOutputDir($vendor = null)
    {
        if ($vendor) {
            $dir = $vendor . '/hiqdev/' . basename(dirname(__DIR__));
        } else {
            $dir = \dirname(__DIR__);
        }

        return $dir . static::OUTPUT_DIR_SUFFIX;
    }

    /**
     * Returns full path to assembled config file.
     * @param string $filename name of config
     * @param string $vendor path to vendor dir
     * @return string absolute path
     */
    public static function path($filename, $vendor = null)
    {
        return static::findOutputDir($vendor) . DIRECTORY_SEPARATOR . $filename . '.php';
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
