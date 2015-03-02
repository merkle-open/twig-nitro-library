<?php

namespace Deniaz\Terrific\Twig\TokenParser;

use Deniaz\Terrific\Twig\Node\ComponentNode;
use \Twig_Token;
use \Twig_TokenParser;

/**
 * Class ComponentTokenParser
 * @package Deniaz\Terrific\Twig\TokenParser
 */
class ComponentTokenParser extends Twig_TokenParser
{
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();
        list($variant, $variables) = $this->parseArguments();
        return new ComponentNode($this->config, $expr, $variant, $variables, $token->getLine(), $this->getTag());
    }

    /**
     * Parses Tag Arguments
     * @return array
     */
    protected function parseArguments()
    {
        $stream = $this->parser->getStream();

        $variant = null;
        if ($stream->test(Twig_Token::STRING_TYPE)) {
            $variant = $stream->expect(Twig_Token::STRING_TYPE)->getValue();
        }

        $variables = null;
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'with')) {
            $variables = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        return [$variant, $variables];
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'component';
    }
}