<?php

namespace Deniaz\Terrific\Twig\Extension;

use Deniaz\Terrific\Twig\TokenParser\ComponentTokenParser;
use Symfony\Component\Finder\Finder;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension;

/**
 * Class ComponentExtension
 * @package Deniaz\Terrific\Twig\Extension
 */
class ComponentExtension extends Twig_Extension
{
    /**
     * @var string Basepath
     */
    private $basePath;

    /**
     * @var array Additional Template Paths
     */
    private $paths;

    /**
     * @var string Twig Template File Extension
     */
    private $fileExtension;

    /**
     * @param        $basePath      Terrific Base Path
     * @param array  $paths         Additional Terrific Template Paths
     * @param string $fileExtension Twig Template File Extension
     */
    public function __construct($basePath, $paths = [], $fileExtension = '.html.twig')
    {
        $this->basePath = $basePath . '/';
        $this->paths = $paths;
        $this->fileExtension = $fileExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'component';
    }

    /**
     * Adds new Filesystem Loader to the Twig Environment.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment)
    {
        /** @var \Twig_Loader_Chain $currentLoader */
        $currentLoader = $environment->getLoader();
        $components = $this->paths;
        $splendidLoader = new Twig_Loader_Filesystem();

        foreach ($components as $component) {
            $splendidLoader->addPath($this->basePath . $component->path);
        }

        $currentLoader->addLoader($splendidLoader);
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
