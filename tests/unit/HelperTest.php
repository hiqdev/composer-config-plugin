<?php

namespace hiqdev\composer\config\tests\unit;

use hiqdev\composer\config\utils\Helper;
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
}
