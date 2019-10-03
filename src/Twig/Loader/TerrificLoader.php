<?php

namespace Deniaz\Terrific\Twig\Loader;

use Deniaz\Terrific\Provider\TemplateInformationProviderInterface;
use Twig_Error_Loader;
use Twig_Loader_Filesystem;

/**
 * TerrificLoader searches for templates on the filesystem within a terrific structure. Since the templates are stored
 * on the filesystem nonetheless, TerrificLoader extends Twig's Twig_Loader_Filesystem.
 *
 * Class TerrificLoader.
 *
 * @package Deniaz\Terrific\Twig\Loader
 */
final class TerrificLoader extends Twig_Loader_Filesystem {
  /**
   * @var string
   */
  private $fileExtension = 'html.twig';

  /**
   * TerrificLoader constructor.
   *
   * @param \Deniaz\Terrific\Provider\TemplateInformationProviderInterface $locator
   */
  public function __construct(TemplateInformationProviderInterface $locator) {
    parent::__construct($locator->getPaths());
    $this->fileExtension = $locator->getFileExtension();
  }

  /**
   * {@inheritdoc}
   */
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
      if (is_readable($fullPath) && $realPath !== FALSE) {
        return $this->cache[$name] = $realPath;
      }
    }

    $this->errorCache[$name] = sprintf('Unable to find component "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace]));

    throw new Twig_Error_Loader($this->errorCache[$name]);
  }

}
