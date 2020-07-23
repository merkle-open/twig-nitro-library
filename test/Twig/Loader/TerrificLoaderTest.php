<?php

namespace Namics\Test\Terrific\Twig\Loader;

use Namics\Terrific\Provider\TemplateInformationProviderInterface;
use Namics\Terrific\Twig\Loader\TerrificLoader;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class TerrificLoaderTest.
 *
 * @package Namics\Test\Terrific\Twig\Loader
 * @coversDefaultClass \Namics\Terrific\Twig\Loader\TerrificLoader
 */
class TerrificLoaderTest extends TestCase {

  /**
   * The file paths.
   *
   * @var array
   */
  protected const PATHS = [
    __DIR__ . '/Fixtures/atoms',
    __DIR__ . '/Fixtures/molecules',
  ];

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
    $provider->method('getPaths')->willReturn(self::PATHS);
    $provider->method('getFileExtension')->willReturn('html');

    return $provider;
  }

  /**
   * Get the terrific loader.
   *
   * @return \Namics\Terrific\Twig\Loader\TerrificLoader
   */
  protected function getTerrificLoader(): TerrificLoader {
    return new TerrificLoader($this->getTemplateInformationProviderMock());
  }

  /**
   * Test the paths.
   */
  public function testPaths(): void {
    /** @var \Namics\Terrific\Twig\Loader\TerrificLoader $loader */
    $loader = $this->getTerrificLoader();

    $this->assertEquals(self::PATHS, $loader->getPaths(), 'Paths are not identical.');

    /** @var \Twig\Source $source */
    $source = $loader->getSourceContext('molecule');
    $this->assertEquals("--molecule_content--\n", $source->getCode(), 'Molecule content is not identical.');

    /** @var \Twig\Source $source */
    $source = $loader->getSourceContext('atom');
    $this->assertEquals("--atom_content--\n", $source->getCode(), 'Atom content is not identical.');

    // Check if the cache is invoked.
    /** @var \Twig\Source $source */
    $source = $loader->getSourceContext('atom');
    $this->assertEquals("--atom_content--\n", $source->getCode(), 'Cache for atom content was not invoked.');

    try {
      $loader->getSourceContext('fake-component');
    }
    catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e, 'Exception "Twig_Error_Loader" was not thrown.');
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage(), 'Exception message is not identical.');
    }

    // Check if the cache is invoked.
    try {
      $loader->getSourceContext('fake-component');
    }
    catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e, 'Exception "Twig_Error_Loader" was not thrown.');
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage(), 'Exception message is not identical.');
    }
  }

}
