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
use hiqdev\composer\config\readers\EnvReader;
use hiqdev\composer\config\readers\JsonReader;
use hiqdev\composer\config\readers\PhpReader;
use hiqdev\composer\config\readers\ReaderFactory;
use hiqdev\composer\config\readers\YamlReader;

/**
 * ReaderFactoryTest.
 */
class ReaderFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $builder;

    public function testCreate()
    {
        $this->builder = new Builder();

        $env    = $this->checkGet('.env',   EnvReader::class);
        $json   = $this->checkGet('.json',  JsonReader::class);
        $yml    = $this->checkGet('.yml',   YamlReader::class);
        $yaml   = $this->checkGet('.yaml',  YamlReader::class);
        $php    = $this->checkGet('.php',   PhpReader::class);
        $php2   = $this->checkGet('.php',   PhpReader::class);

        $this->assertSame($php, $php2);
        $this->assertSame($yml, $yaml);
    }

    public function checkGet(string $name, string $class)
    {
        $reader = ReaderFactory::get($this->builder, $name);
        $this->assertInstanceOf($class, $reader);
        $this->assertSame($this->builder, $reader->getBuilder());

        return $reader;
    }
}
