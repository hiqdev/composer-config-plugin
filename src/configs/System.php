<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\configs;

/**
 * System class represents system configuration files:
 * __files, aliases, packages.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class System extends Config
{
    public function setValue(string $name, $value)
    {
        $this->values[$name] = $value;
    }

    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public function mergeValues(array $values)
    {
        $this->values = array_merge($this->values, $values);
    }

    protected function writeFile(string $path, array $data)
    {
        $this->writePhpFile($path, $data, false);
    }

    public function load(array $paths = [])
    {
        $path = $this->getOutputPath();
        if (!file_exists($path)) {
            return $this;
        }

        $this->values = array_merge($this->loadFile($path), $this->values);

        return $this;
    }

    public function build()
    {
        $this->values = $this->substituteOutputDirs($this->values);

        return $this;
    }
}
