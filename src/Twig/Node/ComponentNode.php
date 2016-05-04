<?php

namespace Deniaz\Terrific\Twig\Node;

use Deniaz\Terrific\Provider\ContextProviderInterface;
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

    private $ctxProvider;
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
        ContextProviderInterface $ctxProvider,
        Twig_Node_Expression $data = null,
        $only = false,
        $lineno,
        $tag = null)
    {
        parent::__construct(
            ['component' => $component, 'data' => $data],
            ['only' => (bool)$only],
            $lineno,
            $tag
        );

        $this->ctxProvider = $ctxProvider;
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
        $compiler
            ->raw('->display($tContext);')
            ->raw("\n\n");

        $compiler->addDebugInfo($this->getNode('component'));
    }

    protected function createTerrificContext(Twig_Compiler $compiler)
    {
        $compiler
            ->addIndentation()
            ->raw('$tContext = $context;')
            ->raw("\n");

        $this->ctxProvider->compile(
          $compiler,
          $this->getNode('component'),
          $this->getNode('data'),
          $this->getAttribute('only')
        );
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
}
