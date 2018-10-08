<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\readers;

use hiqdev\composer\config\exceptions\UnsupportedFileTypeException;

/**
 * YamlReader - reads YAML files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class YamlReader extends AbstractReader
{
    public function readRaw($path)
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new UnsupportedFileTypeException('for YAML support require `symfony/yaml` in your composer.json');
        }

        return \Symfony\Component\Yaml\Yaml::parse($this->getFileContents($path));
    }
}
