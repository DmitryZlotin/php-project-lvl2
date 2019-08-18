<?php

namespace Differ\Tests;

use \PHPUnit\Framework\TestCase;

use function Differ\genDiff;
use function Differ\arrayToPretty;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $beforePath = __DIR__ . "/Files/before.json";
        $afterPath = __DIR__ . "/Files/after.json";
        $prettyResult = __DIR__ . "/Files/prettyResult";
        $exepted = file_get_contents($prettyResult);

        $this->assertEquals($exepted, genDiff($beforePath, $afterPath, "pretty"));
    }
}
