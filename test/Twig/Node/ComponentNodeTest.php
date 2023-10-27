<?php

declare(strict_types=1);

namespace Namics\Test\Terrific\Twig\Node;

use Namics\Terrific\Twig\Node\ComponentNode;
use Namics\Test\Terrific\Twig\TwigTestBase;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;

/**
 * Class ComponentNodeTest.
 *
 * @package Namics\Test\Terrific\Twig\Node
 * @coversDefaultClass \Namics\Terrific\Twig\Node\ComponentNode
 */
class ComponentNodeTest extends TwigTestBase {

  /**
   * The constructor.
   *
   * @covers ::__construct
   */
  public function testConstructor() {
    $ctxProvider = $this->getContextProviderMock();

    $expression = new ConstantExpression('Example', 1);
    $data = new ArrayExpression([], 1);
    $node = new ComponentNode($expression, $ctxProvider, $data, 0, FALSE, NULL);

    /** @var \Twig\Node\Expression\ArrayExpression $nodeData */
    $nodeData = $node->getNode('data');
    $this->assertEquals($nodeData->count(), 0, 'The node data is not empty.');
    $this->assertEquals($expression, $node->getNode('component'), 'The expressions are not identical.');
    $this->assertFalse($node->getAttribute('only'), 'The "only" attribute is not FALSE.');

    $data = new ArrayExpression([
      new ConstantExpression('foo', 1),
      new ConstantExpression(TRUE, 1),
    ], 1);

    $node = new ComponentNode($expression, $ctxProvider, $data,  1, TRUE, NULL);

    $this->assertEquals($data, $node->getNode('data'), 'The data is not identical.');
    $this->assertTrue($node->getAttribute('only'), 'The "only" attribute is not TRUE.');

    $this->getDefaultTest();
  }

  /* public function testComponentVariants() {
    // TODO: test it with a data provider.
    $expectedResults = [
      $this->getDefaultTest(),
      $this->getDefaultOnlyTest(),
      $this->getDataObjectTest(),
      $this->getDataObjectOnlyTest(),
      $this->getVariantTest(),
      $this->getVariantOnlyTest(),
    ];

    // TODO: Write tests.
  } */

  /**
   * Tests the following tag: {% component 'Example' %}.
   *
   * @return array
   */
  private function getDefaultTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ArrayExpression([], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, FALSE,NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$tContext = \$context;
\$this->loadTemplate("Example", null, 1)->display(\$tContext);


EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' only %}.
   *
   * @return array
   */
  private function getDefaultOnlyTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ArrayExpression([], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, TRUE, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display([]);


EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' { foo: 'bar' } %}.
   *
   * @return array
   */
  private function getDataObjectTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ArrayExpression([
      new ConstantExpression('foo', 1),
      new ConstantExpression('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, FALSE, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array_merge(\$context, array("foo" => "bar")));
EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' { foo: 'bar' } only %}.
   *
   * @return array
   */
  private function getDataObjectOnlyTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ArrayExpression([
      new ConstantExpression('foo', 1),
      new ConstantExpression('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, TRUE,NULL);

    return [
      'node' => $node,
       'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array("foo" => "bar"));
EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' 'example-variant' %}.
   *
   * @return array
   */
  private function getVariantTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ConstantExpression('example-foo', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, FALSE, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array_merge(\$context, array("data_source" => "example-foo")));
EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' 'example-variant' only %}.
   */
  private function getVariantOnlyTest() {
    $expr = new ConstantExpression('Example', 1);
    $data = new ConstantExpression('example-foo', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, 1, TRUE, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array("data_source" => "example-foo"));
EOF
    ];
  }

}
