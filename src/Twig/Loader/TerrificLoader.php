<?php

namespace Deniaz\Terrific\Twig\Loader;

use Deniaz\Terrific\TemplateLocatorInterface;

use \Twig_Error_Loader;

final class TerrificLoader extends \Twig_Loader_Filesystem {

  private $fileExtension = NULL;

  /**
   * TerrificLoader constructor.
   * @param TemplateLocatorInterface $locator
   */
  public function __construct(TemplateLocatorInterface $locator) {
    parent::__construct($locator->getPaths());

    $this->fileExtension = $locator->getFileExtension();
  }

  protected function findTemplate($name) {
    $name = $this->normalizeName($name);

    if (isset($this->cache[$name])) {
      return $this->cache[$name];
    }

    if (isset($this->errorCache[$name])) {
      throw new Twig_Error_Loader($this->errorCache[$name]);
    }

    $this->validateName($name);
    $namespace = parent::MAIN_NAMESPACE;

    $terrificPath = $name . '/' . strtolower($name) . '.' . $this->fileExtension;

    foreach ($this->paths[$namespace] as $path) {
      $fullPath = $path . '/' . $terrificPath;
      $realPath = realpath($fullPath);
      if (is_readable($fullPath) && $realPath !== false) {
        return $this->cache[$name] = $realPath;
      }
    }

    $this->errorCache[$name] = sprintf('Unable to find component "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace]));

    throw new Twig_Error_Loader($this->errorCache[$name]);
  }
}
