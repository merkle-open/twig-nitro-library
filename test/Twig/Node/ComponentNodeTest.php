<?php

namespace Namics\Test\Terrific\Twig\Node;

use Drupal\Component\Uuid\Com;
use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\Node\ComponentNode;
use Namics\Test\Terrific\Twig\TwigTestBase;
use Twig\Compiler;
use Twig\Environment;
use Twig_Node_Expression_Constant;
use Twig_Node_Expression_Array;

/**
 * Class ComponentNodeTest.
 *
 * @package Namics\Test\Terrific\Twig\Node
 * @coversDefaultClass \Namics\Terrific\Twig\Node\ComponentNode
 */
class ComponentNodeTest extends TwigTestBase {

  /**
   * The the constructor.
   *
   * @covers ::__construct
   */
  public function testContructor() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $ctxProvider */
    $ctxProvider = $this->getContextProviderMock();

    $expression = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expression, $ctxProvider, NULL, FALSE, 0, NULL);

    $this->assertNull($node->getNode('data'), 'The node data is not Null.');
    $this->assertEquals($expression, $node->getNode('component'), 'The expressions are not identical.');
    $this->assertFalse($node->getAttribute('only'), 'The "only" attribute is not FALSE.');

    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant(TRUE, 1),
    ], 1);

    $node = new ComponentNode($expression, $ctxProvider, $data, TRUE, 1, NULL);

    $this->assertEquals($data, $node->getNode('data'), 'The data is not identical.');
    $this->assertTrue($node->getAttribute('only'), 'The "only" attribute is not TRUE.');

    $this->getDefaultTest();
  }

  public function testComponentVariants() {
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
  }

  /**
   * Tests the following tag: {% component 'Example' %}.
   *
   * @return array
   */
  private function getDefaultTest() {
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), NULL, FALSE, 1, NULL);

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
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), NULL, TRUE, 1, NULL);

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
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, FALSE, 1, NULL);

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
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, TRUE, 1, NULL);

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
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Constant('example-foo', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, FALSE, 1, NULL);

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
   *
   */
  private function getVariantOnlyTest() {
    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Constant('example-foo', 1);
    $node = new ComponentNode($expr, $this->getContextProviderMock(), $data, TRUE, 1, NULL);

    return [
      'node' => $node,
      'source' => <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array("data_source" => "example-foo"));
EOF
    ];
  }

}
