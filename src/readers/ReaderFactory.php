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

    protected static $knownReaders = [
        'env'   => EnvReader::class,
        'php'   => PhpReader::class,
        'json'  => JsonReader::class,
        'yaml'  => YamlReader::class,
        'yml'   => YamlReader::class,
    ];

    public static function get(Builder $builder, $path)
    {
        $type = static::detectType($path);
        $class = static::findClass($type);

        #return static::create($builder, $type);

        $uniqid = $class . ':' . spl_object_hash($builder);
        if (empty(static::$loaders[$uniqid])) {
            static::$loaders[$uniqid] = static::create($builder, $type);
        }

        return static::$loaders[$uniqid];
    }

    public static function detectType($path)
    {
        if (strncmp(basename($path), '.env.', 5) === 0) {
            return 'env';
        }

        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function findClass($type)
    {
        if (empty(static::$knownReaders[$type])) {
            throw new UnsupportedFileTypeException("unsupported file type: $type");
        }

        return static::$knownReaders[$type];
    }

    public static function create(Builder $builder, $type)
    {
        $class = static::findClass($type);

        return new $class($builder);
    }
}
