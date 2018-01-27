<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use hiqdev\composer\config\exceptions\CircularDependencyException;

/**
 * Resolver class.
 * Reorders files according to their cross dependencies
 * and resolves `$name` pathes.
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Resolver
{
    protected $order = [];

    protected $deps = [];

    protected $following = [];

    public function __construct(array $files)
    {
        $this->files = $files;

        $this->collectDeps();
        foreach (array_keys($this->files) as $name) {
            $this->followDeps($name);
        }
    }

    public function get()
    {
        $result = [];
        foreach ($this->order as $name) {
            $result[$name] = $this->resolveDeps($this->files[$name]);
        }

        return $result;
    }

    protected function resolveDeps(array $paths)
    {
        foreach ($paths as &$path) {
            $dep = $this->isDep($path);
            if ($dep) {
                $path = Builder::path($dep);
            }
        }

        return $paths;
    }

    protected function followDeps($name)
    {
        if (isset($this->order[$name])) {
            return;
        }
        if (isset($this->following[$name])) {
            throw new CircularDependencyException($name . ' ' . implode(',', $this->following));
        }
        $this->following[$name] = $name;
        if (isset($this->deps[$name])) {
            foreach ($this->deps[$name] as $dep) {
                $this->followDeps($dep);
            }
        }
        $this->order[$name] = $name;
        unset($this->following[$name]);
    }

    protected function collectDeps()
    {
        foreach ($this->files as $name => $paths) {
            foreach ($paths as $path) {
                $dep = $this->isDep($path);
                if ($dep) {
                    if (!isset($this->deps[$name])) {
                        $this->deps[$name] = [];
                    }
                    $this->deps[$name][$dep] = $dep;
                }
            }
        }
    }

    protected function isDep($path)
    {
        return 0 === strncmp($path, '$', 1) ? substr($path, 1) : false;
    }
}
