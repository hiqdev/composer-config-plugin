<?php

namespace hiqdev\composer\config\tests\unit;

use hiqdev\composer\config\utils\Helper;
use hiqdev\composer\config\utils\RemoveArrayKeys;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testExportClosure(): void
    {
        $params = ['test' => 42];
        $closure = static function () use ($params) {
            return $params['test'];
        };

        $exportedClosure = Helper::exportVar($closure);

        $this->assertSameWithoutLE("static function () use (\$params) {\n            return \$params['test'];\n        }", $exportedClosure);
    }

    private function assertSameWithoutLE($expected, $actual, string $message = ''): void
    {
        $expected = preg_replace('/\R/', "\n", $expected);
        $actual = preg_replace('/\R/', "\n", $actual);
        $this->assertSame($expected, $actual, $message);
    }

    public function testFixRemoveArrayKeys()
    {
        $config = [
            'a' => '1',
            'b' => '2',
            'c' => [
                'd' => 4,
                'remove' => new RemoveArrayKeys(),
                'e' => 5,
            ],
        ];

        $fixed = $config;
        unset($fixed['c']['remove']);
        $fixed['c'] = array_values($fixed['c']);

        $this->assertEquals($fixed, Helper::fixConfig($config));
    }
}
