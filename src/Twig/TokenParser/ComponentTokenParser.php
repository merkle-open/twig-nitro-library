<?php

namespace Deniaz\Terrific\Twig\TokenParser;

use Deniaz\Terrific\Twig\Node\ComponentNode;
use \Twig_Token;
use \Twig_TokenParser;

/**
 * Class ComponentTokenParser
 * @package Deniaz\Terrific\Twig\TokenParser
 */
final class ComponentTokenParser extends Twig_TokenParser
{
    /**
     * @var string Twig Template File Extension
     */
    private $fileExtension;

    /**
     * @param string $fileExtension Twig Template File Extension
     */
    public function __construct($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

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
     * Parses Tag Arguments
     * @return array
     */
    protected function parseArguments()
    {
        $stream = $this->parser->getStream();

        $data = null;
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'with')) {
            $data = $this->parser->getExpressionParser()->parseExpression();
        }

        $only = false;
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'only')) {
            $only = true;
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return [ $data, $only ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'component';
    }
}
