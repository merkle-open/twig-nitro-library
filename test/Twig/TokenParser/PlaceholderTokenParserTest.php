<?php

namespace Namics\Test\Terrific\Twig\TokenParser;

use Namics\Test\Terrific\Twig\TwigTestBase;

/**
 * Class PlaceholderTokenParserTest.
 *
 * @package Namics\Test\Terrific\Twig\TokenParser
 * @coversDefaultClass \Namics\Terrific\Twig\TokenParser\PlaceholderTokenParserTest
 */
class PlaceholderTokenParserTest extends TwigTestBase {

  /**
   * Test the tag.
   *
   * @covers ::getTag
   */
  public function testGetTag() {
    $parser = $this->getPlaceholderTokenParser();
    $this->assertEquals('placeholder', $parser->getTag());
  }

}
