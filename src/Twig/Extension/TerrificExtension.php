<?php

namespace Deniaz\Terrific\Twig\Extension;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;
use \Twig_Extension;

/**
 * TerrificExtension adds Terrific Features to the Twig Environment. Currently only the ComponentTokenParser is added,
 * which results in the additional component tag.
 *
 * Class TerrificExtension
 * @package Deniaz\Terrific\Twig\Extension
 *
 * @author Robert Vogt <robert.vogt@namics.com>
 */
final class TerrificExtension extends Twig_Extension
{
    private $ctxProvider;

    /**
     * TerrificExtension constructor.
     * @param ContextProviderInterface $ctxProvider
     * @TODO: Default Provicer
     */
    public function __construct(ContextProviderInterface $ctxProvider)
    {
        $this->ctxProvider = $ctxProvider;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'terrific';
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new ComponentTokenParser($this->ctxProvider),
        ];
    }
}
