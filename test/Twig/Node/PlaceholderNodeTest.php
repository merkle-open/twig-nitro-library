<?php

namespace Namics\Test\Terrific\Twig\Node;

use Namics\Terrific\Twig\Node\PlaceholderNode;
use Namics\Test\Terrific\Twig\TwigTestBase;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;

/**
 * Class PlaceholderNodeTest.
 *
 * @package Namics\Test\Terrific\Twig\Node
 * @coversDefaultClass \Namics\Terrific\Twig\Node\PlaceholderNodeTest
 */
class PlaceholderNodeTest extends TwigTestBase {

  /**
   * The constructor.
   *
   * @covers ::__construct
   */
  public function testConstructor() {
    $ctxProvider = $this->getContextProviderMock();

    $attributes = [];
    $nodes = [];
    $node = new PlaceholderNode($attributes, $nodes, 0, NULL);

    $this->getDefaultTest();
  }

  /**
   * Tests the following tag: {% placeholder %}.
   *
   * @return array
   */
  private function getDefaultTest() {
    $attributes = [];
    $nodes = [];
    $node = new PlaceholderNode($attributes, $nodes, 0, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF


EOF
    ];
  }

}
