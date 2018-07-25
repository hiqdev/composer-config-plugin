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

use hiqdev\composer\config\Builder;

/**
 * EnvReader - reads `.env` files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class EnvReader extends AbstractReader
{
    public function readRaw($path)
    {
        if (!class_exists('Dotenv\Dotenv')) {
            throw new UnsupportedFileTypeException('for .env support require `vlucas/phpdotenv` in your composer.json');
        }
        $info = pathinfo($path);
        $dotenv = new \Dotenv\Dotenv($info['dirname'], $info['basename']);
        $oldenvs = $_ENV;
        $dotenv->load();
        $newenvs = $_ENV;

        return array_diff_assoc($newenvs, $oldenvs);
    }
}
