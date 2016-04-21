<?php

namespace Deniaz\Terrific\Twig\Loader;

use Deniaz\Terrific\TemplateLocatorInterface;

final class TerrificLoader extends \Twig_Loader_Filesystem
{

  /**
   * TerrificLoader constructor.
   * @param TemplateLocatorInterface $locator
   */
  public function __construct(TemplateLocatorInterface $locator)
  {
      parent::__construct($locator->getPaths());
  }

    public function getSource($name)
    {
        //var_dump($name);
    return parent::getSource($name);
    }
}
