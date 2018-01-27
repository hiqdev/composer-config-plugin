<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\exceptions;

/**
 * Circular dependency exception.
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class CircularDependencyException extends Exception
{
}
