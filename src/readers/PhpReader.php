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
 * PhpReader - reads PHP files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PhpReader extends AbstractReader
{
    public function readRaw($__path, Builder $builder)
    {
        /// Expose variables to be used in configs
        extract($builder->getVars());

        return require $__path;
    }
}
