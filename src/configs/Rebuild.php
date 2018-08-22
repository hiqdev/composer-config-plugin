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
 * Rebuild class represents __rebuild.php script.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Rebuild extends Config
{
    protected function writeFile(string $path, array $data): void
    {
        static::putFile($path, file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '__rebuild.php'));
    }
}
