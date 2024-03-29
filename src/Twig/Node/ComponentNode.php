<?php

declare(strict_types=1);

namespace Namics\Terrific\Twig\Node;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TerrificCompiler;
use Namics\Terrific\Twig\TerrificCompilerInterface;
use Namics\Terrific\Twig\Utility\ExpressionHandler;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;

/**
 * ComponentNode represents a component node.
 *
 * Class ComponentNode.
 *
 * @package Namics\Terrific\Twig\Node
 */
final class ComponentNode extends Node implements NodeOutputInterface {

  /**
   * The context provider.
   *
   * @var \Namics\Terrific\Provider\ContextProviderInterface
   */
  private ContextProviderInterface $ctxProvider;

  /**
   * The expression handler.
   *
   * Should not be accessed directly by methods.
   * Except the getter.
   *
   * @var \Namics\Terrific\Twig\Utility\ExpressionHandler
   */
  private ExpressionHandler $expressionHandler;

  /**
   * ComponentNode constructor.
   *
   * @param \Twig\Node\Node $component
   *   Expression representing the Component's Identifier.
   * @param \Namics\Terrific\Provider\ContextProviderInterface $ctxProvider
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
    Node $data,
    int $lineno,
    bool $only = FALSE,
    string $tag = NULL
  ) {
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
  public function compile(Compiler $compiler): void {
    $terrificCompiler = TerrificCompiler::create($compiler);

    $twigCompiler = $terrificCompiler->getTwigCompiler();

    $twigCompiler->addDebugInfo($this);

    // Create data.
    $this->createTerrificContext($terrificCompiler);

    // Load component template.
    $this->addGetTemplate($terrificCompiler);

    $twigCompiler
      ->raw('->display(' . ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ');')
      ->raw("\n\n");

    $twigCompiler->addDebugInfo($this->getNode('component'));
  }

  /**
   * Makes the data for the component available to it.
   *
   * @param \Namics\Terrific\Twig\TerrificCompilerInterface $terrificCompiler
   *   The Terrific Twig compiler.
   */
  protected function createTerrificContext(TerrificCompilerInterface $terrificCompiler): void {
    $twigCompiler = $terrificCompiler->getTwigCompiler();

    $twigCompiler
      ->write('')
      ->raw(ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ' = $context;')
      ->raw("\n");

    $this->ctxProvider->compile(
      $twigCompiler,
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
   * @param TerrificCompilerInterface $terrificCompiler
   *   The Terrific Twig compiler.
   * @throws SyntaxError
   */
  protected function addGetTemplate(TerrificCompilerInterface $terrificCompiler): void {
    $twigCompiler = $terrificCompiler->getTwigCompiler();

    $twigCompiler->write('$this->loadTemplate(');

    $this->compileComponentName($terrificCompiler);
    $terrificCompiler->compileConcatFileExtensionString();

    $twigCompiler
      ->raw(', ')
      ->repr($this->getTemplateName())
      ->raw(', ')
      ->repr($this->getTemplateLine())
      ->raw(')');
  }

  /**
   * Compiles the component name.
   *
   * @param \Namics\Terrific\Twig\TerrificCompilerInterface $terrificCompiler
   *   The Terrific Twig compiler.
   *
   * @throws \Twig\Error\SyntaxError
   *   If the node name uses an unsupported format.
   */
  protected function compileComponentName(TerrificCompilerInterface $terrificCompiler): void {
    $node = $this->getNode('component');

    /* If a variable is used for component name,
    use its value (is inside Terrifc context "$tContext") for template name.
    E.g. {% component myTwigComponentName { dataItem: 'my string' } %} */
    if ($node instanceof NameExpression) {
      $terrificCompiler->compileNameExpressionAsContextVariable($node);
    }
    /* If a static string (constant) is used for component name,
    compile it (prints it).
    E.g. {% component 'myComponent' { dataItem: 'my string' } %} */
    elseif ($node instanceof ConstantExpression) {
      $terrificCompiler->getTwigCompiler()->subcompile($node);
    }
    /* If an object object/array used for component name,
    use its value (is inside Terrifc context "$tContext") for template name.
    E.g. {% component myTwigObject.anObjectProperty.myTwigComponentName { dataItem: 'my string' } %} */
    elseif ($node instanceof GetAttrExpression) {
      $terrificCompiler->compileGetAttrExpressionAsContextVariable($node);
    }
    else {
      throw new SyntaxError('An unsupported data type was used for name to call a Twig Nitro component.');
    }
  }

  /**
   * Returns the expression handler.
   */
  protected function getExpressionHandler(): ExpressionHandler {
    if (!$this->expressionHandler instanceof ExpressionHandler) {
      $this->expressionHandler = ExpressionHandler::create();
    }

    return $this->expressionHandler;
  }

}
