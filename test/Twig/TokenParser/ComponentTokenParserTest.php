<?php

namespace Namics\Test\Terrific\Twig\TokenParser;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
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
    /** @var \Namics\Terrific\Twig\TokenParser\ComponentTokenParser $parser */
    $parser = $this->getComponentTokenParser();
    $this->assertEquals('component', $parser->getTag());
  }

  // TODO: write test.
  // Public function testParse()
  //    {
  //        $tokenParser = new ComponentTokenParser();
  //        $parser = $this->getMockBuilder('Twig_Parser')->getMock();
  //        $token = $this->getMockBuilder('Twig_Token')->getMock();
  //
  //        $tokenParser->setParser($parser);
  //        $this->assertInstanceOf(ComponentNode::class, $tokenParser->parse($token));
  //    }

}
