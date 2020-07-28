<?php

namespace Namics\Test\Terrific\Twig\Extension;

use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use Namics\Test\Terrific\Twig\TwigTestBase;

/**
 * Class TerrificExtensionTest.
 *
 * @package Namics\Test\Terrific\Twig\Extension
 * @coversDefaultClass \Namics\Terrific\Twig\Extension\TerrificExtension
 */
class TerrificExtensionTest extends TwigTestBase {

  /**
   * Test extension name return value.
   *
   * @covers ::getName
   */
  public function testGetName(): void {
    $twigExtension = $this->getTwigExtension();

    $this->assertEquals('terrific', $twigExtension->getName(), 'Returned Twig extension name is not identical.');
  }

  /**
   * Test component token parser.
   *
   * @covers ::getTokenParsers
   */
  public function testGetTokenParsers(): void {
    $twigExtension = $this->getTwigExtension();

    $this->assertContainsOnlyInstancesOf(ComponentTokenParser::class, $twigExtension->getTokenParsers(), 'Token parser does not include only class ComponentTokenParser.');
  }

}
