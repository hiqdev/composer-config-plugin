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

/**
 * Builder assembles config files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Builder
{
    /**
     * @var array list of configs to build: name => array of pathes
     */
    public $configs = [];


    const BASE_DIR_SAMPLE = '<base-dir>';

    protected function assembleFile($name, array $configs)
    {
        $this->data[$name] = call_user_func_array(['\\hiqdev\\composer\\config\\Helper', 'mergeConfig'], $configs);
        $this->writeFile($name, (array) $this->data[$name]);
    }

    /**
     * Writes config file.
     * @param string $path
     * @param array $data
     */
    protected function writeFile($path, array $data)
    {
        $path = $this->buildOutputPath($path);
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        $array = str_replace("'" . self::BASE_DIR_SAMPLE, '$baseDir . \'', Helper::exportVar($data));
        file_put_contents($path, "<?php\n\n\$baseDir = dirname(dirname(dirname(__DIR__)));\n\nreturn $array;\n");
    }

}
