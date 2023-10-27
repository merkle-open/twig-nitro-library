<?php

declare(strict_types=1);

namespace Namics\Test\Terrific\Twig\TokenParser;

use Namics\Test\Terrific\Twig\TwigTestBase;

/**
 * Class ComponentTokenParserTest.
 *
 * @package Namics\Test\Terrific\Twig\TokenParser
 * @coversDefaultClass \Namics\Terrific\Twig\TokenParser\ComponentTokenParser
 */
class ComponentTokenParserTest extends TwigTestBase {

  /**
   * Test the tag.
   *
   * @covers ::getTag
   */
  public function testGetTag() {
    $parser = $this->getComponentTokenParser();
    $this->assertEquals('component', $parser->getTag());
  }

  /* TODO: Test
  public function testParse() {
    $tokenParser = $this->getComponentTokenParser();

    $token = $this->getMockBuilder('Twig_Token')->getMock();

    $tokenParser->setParser($tokenParser);
    $this->assertInstanceOf(ComponentNode::class, $tokenParser->parse($token));
  } */

}
