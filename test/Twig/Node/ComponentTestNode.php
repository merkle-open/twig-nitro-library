<?php

namespace Namics\Test\Terrific\Twig\Node;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\Node\ComponentNode;
use Twig_Node_Expression_Constant;
use Twig_Node_Expression_Array;
use PHPUnit\Framework\TestCase;

/**
 * Class ComponentNodeTest.
 *
 * @package Namics\Test\Terrific\Twig\Node
 * @coversDefaultClass \Namics\Terrific\Twig\Node\ComponentNode
 */
class ComponentNodeTest extends TestCase {

  /**
   * The the constructor.
   */
  public function testContructor() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expr, $stub, NULL, TRUE, 0, NULL);

    $this->assertNull($node->getNode('data'));
    $this->assertEquals($expr, $node->getNode('component'));
    $this->assertFalse($node->getAttribute('only'));

    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant(TRUE, 1),
    ], 1);
    $node = new ComponentNode($expr, $stub, $data, TRUE, 0, NULL);

    $this->assertEquals($data, $node->getNode('data'));
    $this->assertTrue($node->getAttribute('only'));
  }

  /**
   * Get the tests.
   */
  public function getTests() {
    $tests = [
      $this->getDefaultTest(),
      $this->getDefaultOnlyTest(),
      $this->getDataObjectTest(),
      $this->getDataObjectOnlyTest(),
      $this->getVariantTest(),
      $this->getVariantOnlyTest(),
    ];

    return $tests;
  }

  /**
   * Tests the following tag: {% component 'Example' %}.
   *
   * @return array
   */
  private function getDefaultTest() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expr, $stub, NULL, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(\$context);
EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' only %}.
   *
   * @return array
   */
  private function getDefaultOnlyTest() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $node = new ComponentNode($expr, $stub, NULL, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
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
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $stub, $data, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
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
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Array([
      new Twig_Node_Expression_Constant('foo', 1),
      new Twig_Node_Expression_Constant('bar', 1),
    ], 1);
    $node = new ComponentNode($expr, $stub, $data, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
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
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Constant('example-foo', 1);
    $node = new ComponentNode($expr, $stub, $data, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array_merge(\$context, array("data_source" => "example-foo")));
EOF
    ];
  }

  /**
   * Tests the following tag: {% component 'Example' 'example-variant' only %}.
   *
   * @TODO
   */
  private function getVariantOnlyTest() {
    /** @var \Namics\Terrific\Provider\ContextProviderInterface $stub */
    $stub = $this->getMockBuilder(ContextProviderInterface::class)->getMock();

    $expr = new Twig_Node_Expression_Constant('Example', 1);
    $data = new Twig_Node_Expression_Constant('example-foo', 1);
    $node = new ComponentNode($expr, $stub, $data, TRUE, 0, NULL);

    return [
      $node,
            <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array("data_source" => "example-foo"));
EOF
    ];
  }

}
