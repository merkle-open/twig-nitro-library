<?php

namespace Deniaz\Terrific\Twig\Node;

use \Twig_Compiler;
use \Twig_Node;
use \Twig_NodeOutputInterface;
use \Twig_Node_Expression;
use \Twig_Node_Expression_Array;
use \Twig_Node_Expression_Constant;

/**
 * ComponentNode represents a component node.
 *
 * Class ComponentNode
 * @package Deniaz\Terrific\Twig\Node
 *
 * @author Robert Vogt <robert.vogt@namics.com>
 */
final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface
{
    /**
     * ComponentNode constructor.
     * @param Twig_Node_Expression $component Expression representing the Component's Identifier.
     * @param Twig_Node_Expression|null $data Expression representing the Data Provider.
     * @param bool $only Whether a new Child-Context should be created.
     * @param int $lineno Line number.
     * @param string $tag Tag name associated with the Node.
     */
    public function __construct(
        Twig_Node_Expression $component,
        Twig_Node_Expression $data = null,
        $only = false, $lineno,
        $tag = null)
    {
        parent::__construct(
            ['component' => $component, 'data' => $data],
            ['only' => (bool)$only],
            $lineno,
            $tag
        );
    }

    /**
     * Compiles the node.
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $this->createTerrificContext($compiler);

        $this->addGetTemplate($compiler);
        $compiler->raw('->display($tContext);');
    }

    protected function createTerrificContext(Twig_Compiler $compiler)
    {
        $dataVariant = $this->getNode('data');

        if (false === $this->getAttribute('only')) {
            $compiler->raw('$tContext = $context;');
        } else {
            $compiler->raw('$tContext = [];');
        }

        if (null === $dataVariant) {
            $compiler
                ->raw("\n")
                ->raw('if (isset($context["element"]) && isset($context["element"]["terrific"])) {')
                ->raw("\n\t")
                ->raw(
                    '$tContext = array_merge($tContext, $context["element"]["terrific"]);'
                )
                ->raw("\n")
                ->raw('}');
        } else {
            $this->compileDataVariant($dataVariant, $compiler);
        }
    }

    /**
     * Adds the first expression (Component Identifier) and compiles the template loading logic.
     * @param Twig_Compiler $compiler
     */
    protected function addGetTemplate(Twig_Compiler $compiler)
    {
        $compiler
            ->write('$this->loadTemplate(')
            ->subcompile($this->getNode('component'))
            ->raw(', ')
            ->repr($compiler->getFilename())
            ->raw(', ')
            ->repr($this->getLine())
            ->raw(')');
    }


    /**
     * Compiles the Data Provider, which is either an Expression Array (Data Object) or Expression Constant (Data
     * Variant).
     *
     * @param Twig_Node $node Data Provider Node
     * @param Twig_Compiler $compiler
     * @TODO: Implement some sort of Data Provider.
     */
    protected function compileDataVariant(Twig_Node $node, Twig_Compiler $compiler)
    {
        if ($node instanceof Twig_Node_Expression_Array) {
            $compiler
                ->raw('$tContext = array_merge($tContext, ')
                ->subcompile($node)
                ->raw(');');
        } elseif ($node instanceof Twig_Node_Expression_Constant) {
            $compiler
                ->raw('if (')
                ->raw('isset($context["element"]) && ')
                ->raw('isset($context["element"]["terrific"]) && ')
                ->raw('isset($context["element"]["terrific"]["' . $node->getAttribute('value') . '"])')
                ->raw(') {')
                ->raw('$tContext = array_merge($tContext, ')
                ->raw('$context["element"]["terrific"]["' . $node->getAttribute('value') . '"]')
                ->raw(');')
                ->raw('}');
        }
    }
}
