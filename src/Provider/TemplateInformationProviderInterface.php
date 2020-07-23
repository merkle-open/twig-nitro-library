<?php

namespace Namics\Terrific\Provider;

/**
 * Interface TemplateInformationProviderInterface.
 *
 * Interface to describe Template Information.
 *
 * @package Namics\Terrific\Provider
 * @see Namics\Terrific\Twig\LoaderTerrificLoader
 */
interface TemplateInformationProviderInterface {

  /**
   * Returns a list of paths where templates might be stored.
   *
   * @return array
   *   The path.
   */
  public function getPaths(): array;

  /**
   * Returns the template's file extension.
   *
   * @return string
   *   The file extension.
   */
  public function getFileExtension(): string;

}
