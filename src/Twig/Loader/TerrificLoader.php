<?php

declare(strict_types=1);

namespace Namics\Terrific\Twig\Loader;

use Namics\Terrific\Provider\TemplateInformationProviderInterface;
use Twig\Loader\FilesystemLoader;

/**
 * Searches for templates inside given paths on the filesystem.
 *
 * Includes all subdirectories of given paths recursively.
 *
 * @package Namics\Terrific\Twig\Loader
 */
final class TerrificLoader extends FilesystemLoader {

  /**
   * TerrificLoader constructor.
   *
   * @param \Namics\Terrific\Provider\TemplateInformationProviderInterface $locator
   *   The template locator.
   */
  public function __construct(TemplateInformationProviderInterface $locator) {
    parent::__construct(
      $this->getPathSubdirectories($locator->getPaths())
    );
  }

  /**
   * Returns all subdirectories of given directory recursively.
   *
   * @param string[] $componentDirectoryPaths
   *   Array of paths that contain components in any of their subdirectories.
   *   Or directly inside of them.
   *
   * @return string[]
   *   All subdirectories contained inside given directory.
   */
  private function getPathSubdirectories(array $componentDirectoryPaths): array {
    $paths = [];

    foreach ($componentDirectoryPaths as $componentDirectoryPath) {
      $pathIterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($componentDirectoryPath, \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST,
        // Ignore "Permission denied" errors.
        \RecursiveIteratorIterator::CATCH_GET_CHILD
      );

      $paths[] = $componentDirectoryPath;
      foreach ($pathIterator as $path => $fileInfo) {
        /** @var \SplFileInfo $fileInfo */
        if ($fileInfo->isDir()) {
          /** @var string $path */
          $paths[] = $path;
        }
      }
    }

    return $paths;
  }

}
