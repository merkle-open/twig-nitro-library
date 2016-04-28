<?php

namespace Deniaz\Terrific\Provider;

/**
 * Defines a common interface. Implementations tell the TerrificLoader where to look for templates.
 *
 * Interface TemplateLocatorInterface
 * @package Deniaz\Terrific
 * @see Deniaz\Terrific\Twig\LoaderTerrificLoader
 *
 * @author Robert Vogt <robert.vogt@namics.com>
 */
interface TemplateInformationProviderInterface
{
    /**
     * Returns a list of paths where templates might be stored.
     * @return array
     */
    public function getPaths();

    /**
     * Returns the template's file extension.
     * @return string
     */
    public function getFileExtension();
}
