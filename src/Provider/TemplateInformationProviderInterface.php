<?php

namespace Namics\Terrific\Provider;

/**
 * Interface to describe Template Information.
 *
 * @package Namics\Terrific\Provider
 * @see \Namics\Terrific\Twig\Loader\TerrificLoader
 */
interface TemplateInformationProviderInterface {

  /**
   * Returns a list of paths where templates might be stored.
   *
   * @return string[]
   *   Array of paths.
   */
  public function getPaths(): array;

}
