<?php

namespace Deniaz\Terrific\Twig\Node;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Twig\Compiler;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;

/**
 * ComponentNode represents a component node.
 *
 * Class ComponentNode.
 *
 * @package Deniaz\Terrific\Twig\Node
 */
final class ComponentNode extends Node implements NodeOutputInterface {
  /**
   * The context provider.
   *
   * @var \Deniaz\Terrific\Provider\ContextProviderInterfaceContextVariableProvider
   */
  private $ctxProvider;

  /**
   * ComponentNode constructor.
   *
   * @param \Twig\Node\Node $component
   *   Expression representing the Component's Identifier.
   * @param \Deniaz\Terrific\Provider\ContextProviderInterface $ctxProvider
   *   Context Provider.
   * @param \Twig\Node\Node|null $data
   *   Expression representing the additional data.
   * @param bool $only
   *   Whether a new Child-Context should be created.
   * @param int $lineno
   *   Line Number.
   * @param string $tag
   *   Tag name associated with the node.
   */
  public function __construct(
        Node $component,
        ContextProviderInterface $ctxProvider,
        Node $data = NULL,
        $only = FALSE,
        $lineno,
        $tag = NULL) {
    parent::__construct(
    ['component' => $component, 'data' => $data],
    ['only' => (bool) $only],
    $lineno,
    $tag
    );

    $this->ctxProvider = $ctxProvider;
  }

  /**
   * Compiles the component.
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  public function compile(Compiler $compiler) {
    $compiler->addDebugInfo($this);

    // Create data.
    $this->createTerrificContext($compiler);

    // Load component template.
    $this->addGetTemplate($compiler);

    $compiler
      ->raw('->display(' . ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ');')
      ->raw("\n\n");

    $compiler->addDebugInfo($this->getNode('component'));
  }

  /**
   * Makes the data for the component available to it.
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  protected function createTerrificContext(Compiler $compiler) {
    $compiler
      ->addIndentation()
      ->raw(ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ' = $context;')
      ->raw("\n");

    $this->ctxProvider->compile(
    $compiler,
    $this->getNode('component'),
    $this->getNode('data'),
    $this->getAttribute('only')
    );
  }

  /**
   * Adds the first expression (Component Identifier).
   *
   * And compiles the template loading logic.
   * IMPORTANT: Has to be executed after the Terrific context was created
   * (ComponentNode::createTerrificContext).
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  protected function addGetTemplate(Compiler $compiler) {
    $compiler->write('$this->loadTemplate(');

    /* If a variable is used for component name, use it's value (is inside Terrifc context "$tContext") for template name. */
    if ($this->getNode('component') instanceof NameExpression) {
      $compiler->raw('$tContext["' . $this->getNode('component')->getAttribute('name') . '"]');
    }
    /* If a static string (constant) is used for component name, compile it (prints it). */
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
