<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config\tests\unit;

use Composer\Composer;
use Composer\Config;
use hiqdev\composer\config\Plugin;

/**
 * Class PluginTest.
 */
class PluginTest extends \PHPUnit\Framework\TestCase
{
    private $object;
    private $io;
    private $composer;
    private $event;
    private $packages = [];

    public function setUp()
    {
        parent::setUp();
        $this->composer = new Composer();
        $this->composer->setConfig(new Config(true, getcwd()));
        $this->io = $this->createMock('Composer\IO\IOInterface');
        $this->event = $this->getMockBuilder('Composer\Script\Event')
            ->setConstructorArgs(['test', $this->composer, $this->io])
            ->getMock();

        $this->object = new Plugin();
        $this->object->setPackages($this->packages);
        $this->object->activate($this->composer, $this->io);
    }

    public function testGetPackages()
    {
        $this->assertSame($this->packages, $this->object->getPackages());
    }

    public function testGetSubscribedEvents()
    {
        $this->assertInternalType('array', $this->object->getSubscribedEvents());
    }
}
