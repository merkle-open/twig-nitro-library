<?php

namespace Deniaz\Terrific\Twig\Extension;

use Deniaz\Terrific\Twig\Loader\TerrificLoader;
use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;
use Symfony\Component\Finder\Finder;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension;

/**
 * Class TerrificExtension
 * @package Deniaz\Terrific\Twig\Extension
 */
final class TerrificExtension extends Twig_Extension
{
    /**
     * @var string Twig Template File Extension
     */
    private $fileExtension;

    /**
     * @param string $fileExtension Twig Template File Extension
     */
    public function __construct($fileExtension = '.html.twig')
    {
        $this->fileExtension = $fileExtension;
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
            new ComponentTokenParser($this->fileExtension)
        ];
    }
}
