<?php

namespace Namics\Test\Terrific\Twig\TokenParser;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use PHPUnit\Framework\TestCase;

/**
 * Class ComponentTokenParserTest.
 *
 * @TODO Maybe implement test for the Parser.
 *
 * @package Namics\Test\Terrific\Twig\TokenParser
 * @coversDefaultClass \Namics\Terrific\Twig\TokenParser\ComponentTokenParser
 */
class ComponentTokenParserTest extends TestCase {

  /**
   * Test the tag.
   */
  public function testGetTag() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();
    /** @var \Namics\Terrific\Twig\TokenParser\ComponentTokenParser $parser */
    $parser = new ComponentTokenParser($stub);
    $this->assertEquals('component', $parser->getTag());
  }

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
