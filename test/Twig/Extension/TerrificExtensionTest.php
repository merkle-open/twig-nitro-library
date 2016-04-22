<?php

namespace Deniaz\Test\Terrific\Twig\Extension;

use Deniaz\Terrific\Twig\Extension\TerrificExtension;
use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;

class TerrificExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsExtensionName()
    {
        $ext = new TerrificExtension();
        $name = $ext->getName();

        $this->assertEquals('terrific', $name);
    }

    public function testReturnsComponentTokenParser()
    {
        $ext = new TerrificExtension();
        $parsers = $ext->getTokenParsers();

        $this->assertContainsOnly(ComponentTokenParser::class, $parsers);
    }
}
