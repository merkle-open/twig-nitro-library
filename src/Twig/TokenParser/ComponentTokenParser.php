<?php

namespace Deniaz\Terrific\Twig\TokenParser;

use Deniaz\Terrific\Twig\Node\ComponentNode;
use Twig_Token;
use Twig_TokenParser;

/**
 * Includes a Terrific Component.
 *
 * Class ComponentTokenParser
 * @package Deniaz\Terrific\Twig\TokenParser
 *
 * @author Robert Vogt <robert.vogt@namics.com>
 */
final class ComponentTokenParser extends Twig_TokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Twig_Token $token)
    {
        $component = $this->parser->getExpressionParser()->parseExpression();
        list($data, $only) = $this->parseArguments();

        return new ComponentNode($component, $data, $only, $token->getLine(), $this->getTag());
    }

    /**
     * Tokenizes the component stream.
     * @return array
     */
    protected function parseArguments()
    {
        $stream = $this->parser->getStream();

        $data = null;
        $only = false;

        if ($stream->test(Twig_Token::BLOCK_END_TYPE)) {
            $stream->expect(Twig_Token::BLOCK_END_TYPE);

            return [$data, $only];
        }

        if ($stream->test(Twig_Token::NAME_TYPE, 'only')) {
            $only = true;
            $stream->next();
            $stream->expect(Twig_Token::BLOCK_END_TYPE);

            return [$data, $only];
        }

        $data = $this->parser->getExpressionParser()->parseExpression();

        if ($stream->test(Twig_Token::NAME_TYPE, 'only')) {
            $only = true;
            $stream->next();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return [$data, $only];
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'component';
    }
}
