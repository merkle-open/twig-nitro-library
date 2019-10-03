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
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
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

        // Create data.
        $this->createTerrificContext($compiler);

        // Load component template.
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
     *
     * IMPORTANT: Has to be executed after the Terrific context was created (ComponentNode::createTerrificContext).
     *
     * @param Twig_Compiler $compiler
     *   The Twig compiler.
     */
    protected function addGetTemplate(Twig_Compiler $compiler)
    {
      $compiler->write('$this->loadTemplate(');

      // If a variable is used for component name, use it's value (is inside Terrifc context "$tContext") for template name.
      if ($this->getNode('component') instanceof NameExpression) {
        $compiler->raw('$tContext["' . $this->getNode('component')->getAttribute('name') . '"]');
      }
      // If a static string (constant) is used for component name, compile it (prints it).
      elseif ($this->getNode('component') instanceof ConstantExpression) {
        $compiler->subcompile($this->getNode('component'));
      }

      $compiler
          ->raw(', ')
          ->repr($compiler->getFilename())
          ->raw(', ')
          ->repr($this->getLine())
          ->raw(')');
    }
}
