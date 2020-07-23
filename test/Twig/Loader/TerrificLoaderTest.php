<?php

namespace Namics\Test\Terrific\Twig\Loader;

use Namics\Terrific\Provider\TemplateInformationProviderInterface;
use Namics\Terrific\Twig\Loader\TerrificLoader;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TerrificLoaderTest.
 *
 * @package Namics\Test\Terrific\Twig\Loader
 * @coversDefaultClass \Namics\Terrific\Twig\Loader\TerrificLoader
 */
class TerrificLoaderTest extends TestCase {

  /**
   * Test the paths.
   */
  public function testPaths() {
    $paths = [
      __DIR__ . '/Fixtures/atoms',
      __DIR__ . '/Fixtures/molecules',
    ];

    /** @var \Namics\Terrific\Provider\TemplateInformationProviderInterface|\PHPUnit\Framework\MockObject\MockObject $stub */
    $stub = $this
      ->getMockBuilder(TemplateInformationProviderInterface::class)
      ->getMock();

    $stub
      ->method('getPaths')
      ->willReturn($paths);

    $stub
      ->method('getFileExtension')
      ->willReturn('html');

    /** @var \Namics\Terrific\Twig\Loader\TerrificLoader $loader */
    $loader = new TerrificLoader($stub);

    $this->assertEquals($paths, $loader->getPaths());

    $source = $loader->getSource('molecule');
    $this->assertEquals("--molecule_content--\n", $source);

    $source = $loader->getSource('atom');
    $this->assertEquals("--atom_content--\n", $source);

    // Check if the cache is invoked.
    $source = $loader->getSource('atom');
    $this->assertEquals("--atom_content--\n", $source);

    try {
      $loader->getSource('fake-component');
    }
    catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e);
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage());
    }

    // Check if the cache is invoked.
    try {
      $loader->getSource('fake-component');
    }
    catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e);
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage());
    }
  }

}
