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

use Closure;
use ReflectionFunction;
use yii\helpers\UnsetArrayValue;
use yii\helpers\ReplaceArrayValue;

/**
 * Helper class.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Helper
{
    /**
     * Merges two or more arrays into one recursively.
     * Based on Yii2 yii\helpers\BaseArrayHelper::merge.
     * @return array the merged array
     */
    public static function mergeConfig(): array
    {
        $args = \func_get_args();
        $res = array_shift($args) ?: [];
        foreach ($args as $items) {
            if (!\is_array($items)) {
                continue;
            }
            foreach ($items as $k => $v) {
                if ($v instanceof UnsetArrayValue) {
                    unset($res[$k]);
                } elseif ($v instanceof ReplaceArrayValue) {
                    $res[$k] = $v->value;
                } elseif (\is_int($k)) {
                    /// XXX skip repeated values
                    if (\in_array($v, $res, true))  {
                        continue;
                    }
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (\is_array($v) && isset($res[$k]) && \is_array($res[$k])) {
                    $res[$k] = self::mergeConfig($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    public static function exportDefines(array $defines): string
    {
        $res = '';
        foreach ($defines as $key => $value) {
            $var = static::exportVar($value);
            $res .= "defined('$key') or define('$key', $var);\n";
        }

        return $res;
    }

    /**
     * Returns a parsable string representation of given value.
     * In contrast to var_dump outputs Closures as PHP code.
     * @param mixed $value
     * @return string
     * @throws \ReflectionException
     */
    public static function exportVar($value): string
    {
        $closures = self::collectClosures($value);
        $res = var_export($value, true);
        if (!empty($closures)) {
            $subs = [];
            foreach ($closures as $key => $closure) {
                $subs["'" . $key . "'"] = self::dumpClosure($closure);
            }
            $res = strtr($res, $subs);
        }

        return $res;
    }

    /**
     * Collects closures from given input.
     * Substitutes closures with a tag.
     * @param mixed $input will be changed
     * @return array array of found closures
     */
    private static function collectClosures(&$input): array
    {
        static $closureNo = 1;
        $closures = [];
        if (\is_array($input)) {
            foreach ($input as &$value) {
                if (\is_array($value) || $value instanceof Closure) {
                    $closures = array_merge($closures, self::collectClosures($value));
                }
            }
        } elseif ($input instanceof Closure) {
            ++$closureNo;
            $key = "--==<<[[((Closure#$closureNo))]]>>==--";
            $closures[$key] = $input;
            $input = $key;
        }

        return $closures;
    }

    /**
     * Dumps closure object to string.
     * Based on http://www.metashock.de/2013/05/dump-source-code-of-closure-in-php/.
     * @param Closure $closure
     * @return string
     * @throws \ReflectionException
     */
    public static function dumpClosure(Closure $closure): string
    {
        $res = 'function (';
        $fun = new ReflectionFunction($closure);
        $args = [];
        foreach ($fun->getParameters() as $arg) {
            $str = '';
            if ($arg->isArray()) {
                $str .= 'array ';
            } elseif ($arg->getClass()) {
                $str .= $arg->getClass()->name . ' ';
            }
            if ($arg->isPassedByReference()) {
                $str .= '&';
            }
            $str .= '$' . $arg->name;
            if ($arg->isOptional()) {
                $str .= ' = ' . var_export($arg->getDefaultValue(), true);
            }
            $args[] = $str;
        }
        $res .= implode(', ', $args);
        $res .= ') {' . PHP_EOL;
        $lines = file($fun->getFileName());
        for ($i = $fun->getStartLine(); $i < $fun->getEndLine(); ++$i) {
            $res .= $lines[$i];
        }

        return rtrim($res, "\r\n ,");
    }
}
