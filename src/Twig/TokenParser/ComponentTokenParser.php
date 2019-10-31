<?php

namespace Deniaz\Terrific\Twig\TokenParser;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Deniaz\Terrific\Twig\Node\ComponentNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Includes a Terrific Component.
 *
 * Class ComponentTokenParser.
 *
 * @package Deniaz\Terrific\Twig\TokenParser
 */
final class ComponentTokenParser extends AbstractTokenParser {
  /**
   * The context provider.
   *
   * @var \Deniaz\Terrific\Provider\ContextProviderInterfaceContextVariableProvider
   */
  private $ctxProvider;

  /**
   * ComponentTokenParser constructor.
   *
   * @param \Deniaz\Terrific\Provider\ContextProviderInterface $ctxProvider
   *   The context provider.
   */
  public function __construct(ContextProviderInterface $ctxProvider) {
    $this->ctxProvider = $ctxProvider;
  }

  /**
   * {@inheritdoc}
   */
  public function parse(Token $token) {
    $component = $this->parser->getExpressionParser()->parseExpression();
    list($data, $only) = $this->parseArguments();

    return new ComponentNode($component, $this->ctxProvider, $data, $only, $token->getLine(), $this->getTag());
  }

  /**
   * Tokenizes the component stream.
   *
   * @return array
   *   Array containing data and only flag.
   */
  protected function parseArguments() {
    $stream = $this->parser->getStream();

    $data = NULL;
    $only = FALSE;

    if ($stream->test(Token::BLOCK_END_TYPE)) {
      $stream->expect(Token::BLOCK_END_TYPE);

      return [$data, $only];
    }

    if ($stream->test(Token::NAME_TYPE, 'only')) {
      $only = TRUE;
      $stream->next();
      $stream->expect(Token::BLOCK_END_TYPE);

      return [$data, $only];
    }

    $data = $this->parser->getExpressionParser()->parseExpression();

    if ($stream->test(Token::NAME_TYPE, 'only')) {
      $only = TRUE;
      $stream->next();
    }

    $stream->expect(Token::BLOCK_END_TYPE);

    return [$data, $only];
  }

  /**
   * {@inheritdoc}
   */
  public function getTag() {
    return 'component';
  }

}
