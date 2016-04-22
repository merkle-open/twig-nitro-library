<?php

namespace Deniaz\Test\Terrific\Twig\TokenParser;
use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;

/**
 * Class ComponentTokenParser.
 *
 * @TODO Maybe implement test for the Parser.
 */
class ComponentTokenParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTag()
    {
        $parser = new ComponentTokenParser();
        $this->assertEquals('component', $parser->getTag());
    }

//    public function testParse()
//    {
//        $tokenParser = new ComponentTokenParser();
//        $parser = $this->getMockBuilder('Twig_Parser')->getMock();
//        $token = $this->getMockBuilder('Twig_Token')->getMock();
//
//        $tokenParser->setParser($parser);
//        $this->assertInstanceOf(ComponentNode::class, $tokenParser->parse($token));
//    }
}
