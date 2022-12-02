<?php

namespace Namics\Terrific\Twig\TokenParser;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\Node\ComponentNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Includes a Terrific Component.
 *
 * Class ComponentTokenParser.
 *
 * @package Namics\Terrific\Twig\TokenParser
 */
final class ComponentTokenParser extends AbstractTokenParser {

  /**
   * The context provider.
   *
   * @var \Namics\Terrific\Provider\ContextProviderInterface
   */
  private $ctxProvider;

  /**
   * ComponentTokenParser constructor.
   *
   * @param \Namics\Terrific\Provider\ContextProviderInterface $ctxProvider
   *   The context provider.
   */
  public function __construct(ContextProviderInterface $ctxProvider) {
    $this->ctxProvider = $ctxProvider;
  }

  /**
   * Creates
   *
   * @return \Namics\Terrific\Twig\Node\ComponentNode
   *   The component node.
   */
  public function parse(Token $token): ComponentNode {
    /** @var \Twig\Node\Expression\ConstantExpression $component */
    $component = $this->parser->getExpressionParser()->parseExpression();
    list($data, $only) = $this->parseArguments();

    return new ComponentNode($component, $this->ctxProvider, $data, $token->getLine(), $only, $this->getTag());
  }

  /**
   * Tokenizes the component stream.
   *
   * @return array
   *   Array containing data and only flag.
   */
  protected function parseArguments(): array {
    $data = NULL;
    $only = FALSE;

    /** @var \Twig\TokenStream $stream */
    $stream = $this->parser->getStream();

    // BLOCK_END_TYPE = delimiter for blocks.
    if ($stream->test(Token::BLOCK_END_TYPE)) {
      $stream->expect(Token::BLOCK_END_TYPE);

      return [$data, $only];
    }

    // NAME_TYPE = name expression.
    if ($stream->test(Token::NAME_TYPE, 'only')) {
      $only = TRUE;
      $stream->next();
      $stream->expect(Token::BLOCK_END_TYPE);

      return [$data, $only];
    }

    /** @var \Twig\Node\Expression\ArrayExpression $data */
    $data = $this->parser->getExpressionParser()->parseExpression();

    if ($stream->test(Token::NAME_TYPE, 'only')) {
      $only = TRUE;
      $stream->next();
    }

    $stream->expect(Token::BLOCK_END_TYPE);

    return [$data, $only];
  }

  /**
   * Returns the component name.
   *
   * @return string
   *   The component name.
   */
  public function getTag(): string {
    return 'component';
  }

}
