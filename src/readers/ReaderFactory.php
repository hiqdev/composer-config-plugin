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
use hiqdev\composer\config\exceptions\UnsupportedFileTypeException;

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

    public static function get(Builder $builder, $path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $class = static::findClass($ext);
        if (empty(static::$loaders[$class])) {
            static::$loaders[$class] = static::create($builder, $ext);
        }

        return static::$loaders[$class];
    }

    public static function findClass($ext)
    {
        if (empty(static::$knownReaders[$ext])) {
            throw new UnsupportedFileTypeException("unsupported extension: $ext");
        }

        return static::$knownReaders[$ext];
    }

    public static function create(Builder $builder, $ext)
    {
        $class = static::findClass($ext);

        return new $class($builder);
    }
}
