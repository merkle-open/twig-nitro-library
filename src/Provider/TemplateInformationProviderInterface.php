<?php

namespace Deniaz\Terrific\Provider;

/**
 * Interface to describe Template Information.
 *
 * Interface TemplateLocatorInterface.
 *
 * @package Deniaz\Terrific
 * @see Deniaz\Terrific\Twig\LoaderTerrificLoader
 */
interface TemplateInformationProviderInterface {

  /**
   * Returns a list of paths where templates might be stored.
   *
   * @return array
   */
  public function getPaths();

  /**
   * Returns the template's file extension.
   *
   * @return string
   */
  public function getFileExtension();

}
