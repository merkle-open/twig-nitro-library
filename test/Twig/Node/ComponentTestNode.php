<?php

namespace Deniaz\Test\Terrific\Twig\Node;

use Deniaz\Terrific\Twig\Node\ComponentNode;
use Twig_Node_Expression_Constant;
use Twig_Node_Expression_Array;

class ComponentNodeTest extends \Twig_Test_NodeTestCase
{
    public function testContructor()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $node = new ComponentNode($expr, null, false, 1);

        $this->assertNull($node->getNode('data'));
        $this->assertEquals($expr, $node->getNode('component'));
        $this->assertFalse($node->getAttribute('only'));

        $data = new Twig_Node_Expression_Array([
            new Twig_Node_Expression_Constant('foo', 1),
            new Twig_Node_Expression_Constant(true, 1),
        ], 1);
        $node = new ComponentNode($expr, $data, true, 1);

        $this->assertEquals($data, $node->getNode('data'));
        $this->assertTrue($node->getAttribute('only'));
    }

    public function getTests()
    {
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
    private function getDefaultTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $node = new ComponentNode($expr, null, false, 1);

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
    private function getDefaultOnlyTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $node = new ComponentNode($expr, null, true, 1);

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
    private function getDataObjectTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $data = new Twig_Node_Expression_Array([
            new Twig_Node_Expression_Constant('foo', 1),
            new Twig_Node_Expression_Constant('bar', 1),
        ], 1);
        $node = new ComponentNode($expr, $data, false, 1);

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
    private function getDataObjectOnlyTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $data = new Twig_Node_Expression_Array([
            new Twig_Node_Expression_Constant('foo', 1),
            new Twig_Node_Expression_Constant('bar', 1),
        ], 1);
        $node = new ComponentNode($expr, $data, true, 1);

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
    private function getVariantTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $data = new Twig_Node_Expression_Constant('example-foo', 1);
        $node = new ComponentNode($expr, $data, false, 1);

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
    private function getVariantOnlyTest()
    {
        $expr = new Twig_Node_Expression_Constant('Example', 1);
        $data = new Twig_Node_Expression_Constant('example-foo', 1);
        $node = new ComponentNode($expr, $data, true, 1);

        return [
            $node,
            <<<EOF
// line 1
\$this->loadTemplate("Example", null, 1)->display(array("data_source" => "example-foo"));
EOF
        ];
    }
}
