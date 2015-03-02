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
     * @var array Terrific Config
     */
    private $config;

    /**
     * @param      $basePath    Base path
     * @param null $config      Terrific Config
     */
    public function __construct($basePath, $config = null)
    {
        $this->basePath = $basePath . '/';
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'component';
    }

    /**
     * Adds new Filesystem Loaders to the Twig Environment based on the Terrific Config File.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment)
    {
        /** @var \Twig_Loader_Chain $currentLoader */
        $currentLoader = $environment->getLoader();
        $components = $this->config->micro->components;
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
            new ComponentTokenParser($this->config)
        ];
    }
}