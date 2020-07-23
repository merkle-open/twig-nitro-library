<?php

namespace Namics\Test\Terrific\Twig\Extension;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\Extension\TerrificExtension;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TerrificExtensionTest.
 *
 * @package Namics\Test\Terrific\Twig\Extension
 * @coversDefaultClass \Namics\Terrific\Twig\Extension\TerrificExtension
 */
class TerrificExtensionTest extends TestCase {

  /**
   * Test extension name return value.
   */
  public function testReturnsExtensionName() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();
    /** @var \Namics\Terrific\Twig\Extension\TerrificExtension $ext */
    $ext = new TerrificExtension($stub);
    $name = $ext->getName();

    $this->assertEquals('terrific', $name);
  }

  /**
   * Test component token parser.
   */
  public function testReturnsComponentTokenParser() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();
    /** @var \Namics\Terrific\Twig\Extension\TerrificExtension $ext */
    $ext = new TerrificExtension($stub);
    $parsers = $ext->getTokenParsers();

    $this->assertContainsOnly(ComponentTokenParser::class, $parsers);
  }

}
