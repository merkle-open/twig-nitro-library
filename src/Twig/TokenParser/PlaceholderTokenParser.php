<?php

namespace Namics\Terrific\Twig\TokenParser;

use Twig\TokenParser\AbstractTokenParser;
use Twig\Token;
use Twig\Node\Node;
use Namics\Terrific\Twig\Node\PlaceholderNode;

/**
 * Includes a Terrific Placeholder.
 *
 * Class PlaceholderTokenParser.
 *
 * @package Namics\Terrific\Twig\TokenParser
 */
final class PlaceholderTokenParser extends AbstractTokenParser {

  /**
   * Creates
   *
   * @return \Namics\Terrific\Twig\Node\PlaceholderNode
   *   The placeholder node.
   */
  public function parse(Token $token) : PlaceholderNode {
    $stream = $this->parser->getStream();
    $attributes = [];

    // Skip dynamic attributes
    while (!$stream->test(Token::BLOCK_END_TYPE)) {
        $stream->next();
    }

    // Expect the closing block tag
    $stream->expect(Token::BLOCK_END_TYPE);

    return new PlaceholderNode($attributes, [], $token->getLine(), $this->getTag());
  }
  /**
   * Returns the placeholder name.
   *
   * @return string
   *   The placeholder name.
   */
  public function getTag(): string {
    return 'placeholder';
  }

}
