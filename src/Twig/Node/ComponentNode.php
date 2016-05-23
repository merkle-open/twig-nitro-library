<?php

/**
 * This file is part of the Terrific Twig package.
 *
 * (c) Robert Vogt <robert.vogt@namics.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
 */
final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface
{
    /**
     * @var ContextProviderInterface Context Variable Provider
     */
    private $ctxProvider;

    /**
     * ComponentNode constructor.
     * @param Twig_Node_Expression $component Expression representing the Component's Identifier.
     * @param ContextProviderInterface $ctxProvider Context Provider.
     * @param Twig_Node_Expression|null $data Expression representing the additional data.
     * @param bool $only Whether a new Child-Context should be created.
     * @param int $lineno Line Number.
     * @param string $tag Tag name associated with the node.
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

    /**
     * @param Twig_Compiler $compiler
     */
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
