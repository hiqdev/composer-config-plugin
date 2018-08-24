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
 * DotEnv class represents output configuration file with ENV values.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class DotEnv extends Config
{
    protected function writeFile(string $path, array $data): void
    {
        $this->writePhpFile($path, $data, false, false);
    }
}
