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

use Dotenv\Dotenv;
use hiqdev\composer\config\exceptions\UnsupportedFileTypeException;

/**
 * EnvReader - reads `.env` files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class EnvReader extends AbstractReader
{
    public function readRaw($path)
    {
        if (!class_exists(Dotenv::class)) {
            throw new UnsupportedFileTypeException('for .env support require `vlucas/phpdotenv` in your composer.json');
        }
        $info = pathinfo($path);
        $dotenv = $this->createDotenv($info['dirname'], $info['basename']);
        $oldenvs = $_ENV;
        $dotenv->load();
        $newenvs = $_ENV;

        return array_diff_assoc($newenvs, $oldenvs);
    }

    /**
     * Creates Dotenv object.
     * Supports both 2 and 3 version of `phpdotenv`
     * @param mixed $dir
     * @param mixed $file
     * @return Dotenv
     */
    private function createDotenv($dir, $file)
    {
        if (method_exists(Dotenv::class, 'create')) {
            return Dotenv::create($dir, $file);
        } else {
            return new Dotenv($dir, $file);
        }
    }
}
