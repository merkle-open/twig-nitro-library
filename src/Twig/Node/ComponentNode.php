<?php

namespace Deniaz\Terrific\Twig\Node;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;
use Twig_NodeOutputInterface;

/**
 * ComponentNode represents a component node.
 *
 * Class ComponentNode.
 *
 * @package Deniaz\Terrific\Twig\Node
 */
final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface {
  /**
   * The context provider.
   *
   * @var \Deniaz\Terrific\Provider\ContextProviderInterfaceContextVariableProvider
   */
  private $ctxProvider;

  /**
   * ComponentNode constructor.
   *
   * @param \Twig_Node_Expression $component
   *   Expression representing the Component's Identifier.
   * @param \Deniaz\Terrific\Provider\ContextProviderInterface $ctxProvider
   *   Context Provider.
   * @param \Twig_Node_Expression|null $data
   *   Expression representing the additional data.
   * @param bool $only
   *   Whether a new Child-Context should be created.
   * @param int $lineno
   *   Line Number.
   * @param string $tag
   *   Tag name associated with the node.
   */
  public function __construct(
        Twig_Node_Expression $component,
        ContextProviderInterface $ctxProvider,
        Twig_Node_Expression $data = NULL,
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
   * @param \Twig_Compiler $compiler
   *   The Twig compiler.
   */
  public function compile(Twig_Compiler $compiler) {
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
   * Makes the data for the component available to it.
   *
   * @param \Twig_Compiler $compiler
   *   The Twig compiler.
   */
  protected function createTerrificContext(Twig_Compiler $compiler) {
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
   * Adds the first expression (Component Identifier).
   *
   * And compiles the template loading logic.
   * IMPORTANT: Has to be executed after the Terrific context was created
   * (ComponentNode::createTerrificContext).
   *
   * @param \Twig_Compiler $compiler
   *   The Twig compiler.
   */
  protected function addGetTemplate(Twig_Compiler $compiler) {
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
