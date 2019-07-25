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

/**
 * Output Dir class.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class OutputDir
{
    /**
     * @var string path to output assembled configs
     */
    protected $dir;

    public function __construct($outputDir = null)
    {
        $this->setOutputDir($outputDir);

    }

    public function setOutputDir($outputDir)
    {
        var_dump(__METHOD__, $outputDir);die;
        $this->outputDir = $outputDir ?: static::findOutputDir();
    }

    public function getOutputDir(): string
    {
        return $this->outputDir;
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

    public function getOutputPath($name)
    {
        return $this->outputDir . DIRECTORY_SEPARATOR . $name . '.php';
    }
}
