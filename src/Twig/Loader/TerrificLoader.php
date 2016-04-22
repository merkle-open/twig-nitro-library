<?php

namespace Deniaz\Terrific\Twig\Loader;

use Deniaz\Terrific\TemplateLocatorInterface;
use \Twig_Error_Loader;

final class TerrificLoader extends \Twig_Loader_Filesystem {

  private $fileExtension = null;
  /**
   * TerrificLoader constructor.
   * @param TemplateLocatorInterface $locator
   */
  public function __construct(TemplateLocatorInterface $locator) {
    parent::__construct($locator->getPaths());

    $this->fileExtension = $locator->getFileExtension();
  }

  protected function findTemplate($name) {
    $throw = func_num_args() > 1 ? func_get_arg(1) : TRUE;
    $name = $this->normalizeName($name);

    if (isset($this->cache[$name])) {
      return $this->cache[$name];
    }

    if (isset($this->errorCache[$name])) {
      if (!$throw) {
        return FALSE;
      }

      throw new Twig_Error_Loader($this->errorCache[$name]);
    }

    $this->validateName($name);

    list($namespace, $shortname) = $this->parseName($name);

    if (!isset($this->paths[$namespace])) {
      $this->errorCache[$name] = sprintf('There are no registered paths for namespace "%s".', $namespace);

      if (!$throw) {
        return FALSE;
      }

      throw new Twig_Error_Loader($this->errorCache[$name]);
    }

    $terrificName = $shortname . '/' . strtolower($shortname) . '.' . $this->fileExtension;


    foreach ($this->paths[$namespace] as $path) {
      if (is_file($path . '/' . $terrificName)) {
        if (FALSE !== $realpath = realpath($path . '/' . $terrificName)) {
          return $this->cache[$name] = $realpath;
        }

        return $this->cache[$name] = $path . '/' . $terrificName;
      }
    }

    $this->errorCache[$name] = sprintf('Unable to find component "%s" (looked into: %s).', $terrificName, implode(', ', $this->paths[$namespace]));

    if (!$throw) {
      return FALSE;
    }

    throw new Twig_Error_Loader($this->errorCache[$name]);
  }
}