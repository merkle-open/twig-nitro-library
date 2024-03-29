<?php

declare(strict_types=1);

namespace Namics\Test\Terrific\Twig\Loader;

use Namics\Terrific\Provider\TemplateInformationProviderInterface;
use Namics\Terrific\Twig\Loader\TerrificLoader;
use Exception;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;

/**
 * Tests the TerrificLoader.
 *
 * @package Namics\Test\Terrific\Twig\Loader
 * @coversDefaultClass \Namics\Terrific\Twig\Loader\TerrificLoader
 */
class TerrificLoaderTest extends TestCase {

  /**
   * Paths returned by the Template Information Provider.
   *
   * @var string[]
   */
  protected const TEMPLATE_INFORMATION_PROVIDER_PATHS = [
    __DIR__ . '/Fixtures/atoms',
    __DIR__ . '/Fixtures/molecules',
  ];

  /**
   * Paths generated by the Terrific loader.
   *
   * @var string[]
   */
  protected const LOADER_PATHS = [
    __DIR__ . '/Fixtures/atoms',
    __DIR__ . '/Fixtures/atoms/atom',
    __DIR__ . '/Fixtures/molecules',
    __DIR__ . '/Fixtures/molecules/molecule',
    __DIR__ . '/Fixtures/molecules/molecule/elements',
  ];

  protected const FIXTURE_ATOM_CONTENT = "--atom_content--\n";
  protected const FIXTURE_MOLECULE_CONTENT = "--molecule_content--\n";
  protected const FIXTURE_MOLECULE_ELEMENT_CONTENT = "--molecule-element_content--\n";

  /**
   * Get the template information provider mock.
   *
   * @return \Namics\Terrific\Provider\TemplateInformationProviderInterface
   *   The template information provider mock.
   */
  protected function getTemplateInformationProviderMock() {
    /** @var \Namics\Terrific\Provider\TemplateInformationProviderInterface|\PHPUnit\Framework\MockObject\MockObject $provider */
    $provider = $this->getMockBuilder(TemplateInformationProviderInterface::class)
      ->getMock();
    $provider->method('getPaths')->willReturn(self::TEMPLATE_INFORMATION_PROVIDER_PATHS);

    return $provider;
  }

  /**
   * Get the terrific loader.
   *
   * @return \Namics\Terrific\Twig\Loader\TerrificLoader
   *   The Terrific loader.
   */
  protected function getTerrificLoader(): TerrificLoader {
    return new TerrificLoader($this->getTemplateInformationProviderMock());
  }

  /**
   * Test the paths generated by the Loader.
   */
  public function testPathGeneration(): void {
    $loader = $this->getTerrificLoader();

    $this->assertEquals(self::LOADER_PATHS, $loader->getPaths(), 'Loader paths are not identical.');
  }

  /**
   * Test the paths.
   */
  public function testTemplateLoading(): void {
    $loader = $this->getTerrificLoader();

    $source = $loader->getSourceContext('molecule.twig');
    $this->assertEquals(self::FIXTURE_MOLECULE_CONTENT, $source->getCode(), 'Molecule content is not identical.');

    $source = $loader->getSourceContext('atom.twig');
    $this->assertEquals(self::FIXTURE_ATOM_CONTENT, $source->getCode(), 'Atom content is not identical.');

    // Check if the cache is invoked.
    $source = $loader->getSourceContext('atom.twig');
    $this->assertEquals(self::FIXTURE_ATOM_CONTENT, $source->getCode(), 'Cache for atom content was not invoked.');

    try {
      $loader->getSourceContext('fake-component.twig');
    }
    catch (Exception $exception) {
      $this->assertInstanceOf(LoaderError::class, $exception, 'Exception "' . LoaderError::class . '" was not thrown.');
      $this->assertStringContainsString('Unable to find template "fake-component.twig"', $exception->getMessage(), 'Exception message is not identical.');
    }

    // Check if the cache is invoked.
    try {
      $loader->getSourceContext('fake-component.twig');
    }
    catch (Exception $exception) {
      $this->assertInstanceOf(LoaderError::class, $exception, 'Exception "' . LoaderError::class . '" was not thrown.');
      $this->assertStringContainsString('Unable to find template "fake-component.twig"', $exception->getMessage(), 'Exception message is not identical.');
    }
  }

  /**
   * Test if templates in component subdirectories are loaded.
   */
  public function testChildTemplateLoading(): void {
    $loader = $this->getTerrificLoader();

    $source = $loader->getSourceContext('molecule-element.twig');
    $this->assertEquals(self::FIXTURE_MOLECULE_ELEMENT_CONTENT, $source->getCode(), 'Child template content is not correct.');
  }

}
