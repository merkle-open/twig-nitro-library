<?php

namespace Deniaz\Test\Terrific\Twig\Extension;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Deniaz\Terrific\Twig\Extension\TerrificExtension;
use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;

class TerrificExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsExtensionName()
    {
        $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();
        $ext = new TerrificExtension($stub);
        $name = $ext->getName();

        $this->assertEquals('terrific', $name);
    }

    public function testReturnsComponentTokenParser()
    {
        $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();
        $ext = new TerrificExtension($stub);
        $parsers = $ext->getTokenParsers();

        $this->assertContainsOnly(ComponentTokenParser::class, $parsers);
    }
}
