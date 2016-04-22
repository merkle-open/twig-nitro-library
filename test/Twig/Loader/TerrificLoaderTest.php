<?php

namespace Deniaz\Test\Terrific\Twig\Loader;

use Deniaz\Terrific\TemplateLocatorInterface;
use Deniaz\Terrific\Twig\Loader\TerrificLoader;
use \Exception;

class TerrificLoaderTest extends \PHPUnit_Framework_TestCase
{
  public function testPaths() {
    $paths = [
      __DIR__ . '/Fixtures/atoms',
      __DIR__ . '/Fixtures/molecules'
    ];

    $stub = $this
      ->getMockBuilder(TemplateLocatorInterface::class)
      ->getMock();

    $stub
      ->method('getPaths')
      ->willReturn($paths);

    $stub
      ->method('getFileExtension')
      ->willReturn('html');

    $loader = new TerrificLoader($stub);

    $this->assertEquals($paths, $loader->getPaths());

    $source = $loader->getSource('molecule');
    $this->assertEquals("--molecule_content--\n", $source);

    $source = $loader->getSource('atom');
    $this->assertEquals("--atom_content--\n", $source);

    $cachedSource = $loader->getSource('atom');
    $this->assertEquals("--atom_content--\n", $source);

    try {
      $loader->getSource('fake-component');
    } catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e);
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage());
    }

    try {
      $loader->getSource('fake-component');
    } catch (Exception $e) {
      $this->assertInstanceOf('Twig_Error_Loader', $e);
      $this->assertContains('Unable to find component "fake-component"', $e->getMessage());
    }
  }
}
