<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\tests\unit\configs;

use hiqdev\composer\config\Builder;
use hiqdev\composer\config\configs\Config;
use hiqdev\composer\config\configs\ConfigFactory;
use hiqdev\composer\config\configs\Defines;
use hiqdev\composer\config\configs\Params;
use hiqdev\composer\config\configs\System;

/**
 * ConfigFactoryTest.
 */
class ConfigFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $builder;

    public function testCreate()
    {
        $this->builder = new Builder();

        $this->checkCreate('common',    Config::class);
        $this->checkCreate('defines',   Defines::class);
        $this->checkCreate('params',    Params::class);
        $this->checkCreate('__files',   System::class);
    }

    public function checkCreate(string $name, string $class)
    {
        $config = ConfigFactory::create($this->builder, $name);
        $this->assertInstanceOf($class, $config);
        $this->assertSame($this->builder, $config->getBuilder());
        $this->assertSame($name, $config->getName());
    }
}
