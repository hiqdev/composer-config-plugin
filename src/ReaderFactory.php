<?php
/**
 * Composer plugin for config assembling.
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use hiqdev\composer\config\exceptions\UnsupportedFileTypeException;
use hiqdev\composer\config\readers\EnvReader;
use hiqdev\composer\config\readers\JsonReader;
use hiqdev\composer\config\readers\PhpReader;
use hiqdev\composer\config\readers\YamlReader;

/**
 * Reader - helper to load data from files of different types.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ReaderFactory
{
    private static $loaders;

    private static $knownReaders = [
        'env'   => EnvReader::class,
        'php'   => PhpReader::class,
        'json'  => JsonReader::class,
        'yaml'  => YamlReader::class,
        'yml'   => YamlReader::class,
    ];

    public static function get($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (empty(static::$loaders[$ext])) {
            static::$loaders[$ext] = static::create($ext);
        }

        return static::$loaders[$ext];
    }

    public static function create($ext)
    {
        if (empty(static::$knownReaders[$ext])) {
            throw new UnsupportedFileTypeException("unsupported extension: $ext");
        }
        $class = static::$knownReaders[$ext];

        return new $class();
    }
}
