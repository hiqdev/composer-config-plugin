<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\utils;

use Closure;
use Riimu\Kit\PHPEncoder\Encoder\Encoder;
use Opis\Closure\ReflectionClosure;

/**
 * Closure encoder for Riimu Kit-PHPEncoder.
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ClosureEncoder implements Encoder
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function supports($value)
    {
        return $value instanceof Closure;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($value, $depth, array $options, callable $encode)
    {
        return (new ReflectionClosure($value))->getCode();
    }
}
