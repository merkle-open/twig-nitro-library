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

        $this->addGetTemplate($compiler);

        $compiler->raw('->display(');
        $this->addTemplateArguments($compiler);
        $compiler->raw(");\n");
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
     * Adds the optional second and third arguments (Data Provider and only-attribute).
     * @param Twig_Compiler $compiler
     */
    protected function addTemplateArguments(Twig_Compiler $compiler)
    {
        $data = $this->getNode('data');

        if (null === $data) {
            $compiler->raw(false === $this->getAttribute('only') ? '$context' : '[]');
        } elseif (false === $this->getAttribute('only')) {
            $compiler->raw('array_merge($context, ');
            $this->transformData($data, $compiler);
            $compiler->raw(')');
        } else {
            $this->transformData($data, $compiler);
        }
    }

    /**
     * Compiles the Data Provider, which is either an Expression Array (Data Object) or Expression Constant (Data
     * Variant).
     *
     * @param Twig_Node_Expression $node Data Provider Node
     * @param Twig_Compiler $compiler
     * @TODO: Implement some sort of Data Provider.
     */
    protected function transformData(Twig_Node_Expression $node, Twig_Compiler $compiler)
    {
        if ($node instanceof Twig_Node_Expression_Array) {
            $compiler->subcompile($node);
        } elseif ($node instanceof Twig_Node_Expression_Constant) {
            $compiler->subcompile(
                new Twig_Node_Expression_Array([
                    new Twig_Node_Expression_Constant('data_source', $this->getLine()),
                    new Twig_Node_Expression_Constant($node->getAttribute('value'), $this->getLine()),
                ], $this->getLine())
            );
        }
    }
}
