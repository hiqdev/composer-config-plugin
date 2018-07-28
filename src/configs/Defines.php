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
 * Defines class represents output configuration file with constant definitions.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Defines extends Config
{
    protected function loadFile($path)
    {
        parent::loadFile($path);
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
            return [];
        }

        $lines = file($path);
        array_shift($lines);

        return $lines;
    }

    protected function writeFile(string $path, array $data)
    {
        static::putFile($path, "<?php\n\n" . trim(implode('', $data)));
    }
}
