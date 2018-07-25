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
use hiqdev\composer\config\exceptions\FailedReadException;

/**
 * Reader - helper to read data from files of different types.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
abstract class AbstractReader
{
    /**
     * @var Builder
     */
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function read($path)
    {
        $skippable = 0 === strncmp($path, '?', 1) ? '?' : '';
        if ($skippable) {
            $path = substr($path, 1);
        }

        if (is_readable($path)) {
            $res = $this->readRaw($path);

            return is_array($res) ? $res : [];
        }

        if (empty($skippable)) {
            throw new FailedReadException("failed read file: $path");
        }

        return [];
    }

    public function getFileContents($path)
    {
        $res = file_get_contents($path);
        if (false === $res) {
            throw new FailedReadException("failed read file: $path");
        }

        return $res;
    }

    abstract public function readRaw($path);
}
