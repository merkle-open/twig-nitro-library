<?php

declare(strict_types=1);

namespace Namics\Terrific\Twig;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\Data\VariableNameAndArrayKeysPair;
use Namics\Terrific\Twig\TwigExtension\TerrificExtension;
use Namics\Terrific\Twig\Utility\ExpressionHandler;
use Twig\Compiler;
use Twig\Error\Error;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;

/**
 * Extends the Twig compiler with additional functionality.
 *
 * @package \Namics\Terrific\Twig
 */
class TerrificCompiler implements TerrificCompilerInterface {

  /**
   * The base Twig compiler.
   *
   * @var \Twig\Compiler
   */
  protected Compiler $compiler;

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
   * TerrificCompiler constructor.
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  protected function __construct(Compiler $compiler) {
    $this->compiler = $compiler;
    $this->expressionHandler = ExpressionHandler::create();
  }

  /**
   * Instantiates a new object.
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  public static function create(Compiler $compiler): self {
    return new self($compiler);
  }

  /**
   * Compiles NameExpression as a variable to access.
   *
   * @param \Twig\Node\Expression\NameExpression $expression
   *   The expression to compile.
   * @param string $contextVariable
   *   The PHP variable to use as base for adding the array keys.
   *   Defaults to the Terrific context.
   * @throws Error
   */
  public function compileNameExpressionAsContextVariable(NameExpression $expression, string $contextVariable = ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE): void {
    $variableName = $this->getExpressionHandler()->getVariableNameFromNameExpression($expression);

    $this->compileAsContextVariable(VariableNameAndArrayKeysPair::createVariable($variableName), $contextVariable);
  }

  /**
   * Compiles GetAttrExpression as a variable to access.
   *
   * @param \Twig\Node\Expression\GetAttrExpression $expression
   *   The expression to compile.
   * @param string $contextVariable
   *   The PHP variable to use as base for adding the array keys.
   *   Defaults to the Terrific context.
   */
  public function compileGetAttrExpressionAsContextVariable(GetAttrExpression $expression, string $contextVariable = ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE): void {
    $variableAndArrayKeys = $this->getExpressionHandler()->buildGetAttrExpressionArrayKeyPair($expression);

    $this->compileAsContextVariable($variableAndArrayKeys, $contextVariable);
  }

  /**
   * Compiles given variable as a Twig context variable.
   *
   * @param \Namics\Terrific\Twig\Data\VariableNameAndArrayKeysPair $variableNameAndArrayKeysPair
   *   Object containing the variable name and optionally array keys.
   * @param string $contextVariable
   *   The PHP variable to use as base for adding the array keys.
   */
  protected function compileAsContextVariable(VariableNameAndArrayKeysPair $variableNameAndArrayKeysPair, string $contextVariable): void {
    $contextArrayKeys = $variableNameAndArrayKeysPair->toTwigContextArrayKeysString();

    $this->getTwigCompiler()->raw($contextVariable . $contextArrayKeys);
  }

  /**
   * Compiles and adds NameExpression as a variable.
   *
   * To the Terrific Twig context.
   *
   * @param \Twig\Node\Expression\NameExpression $expression
   *   The expression to compile.
   * @param string|null $variableDoesNotExistErrorMessage
   *   Custom error message that is used as exception message
   *   when the given variable does not exist.
   */
  public function compileAndMergeNameExpressionToContext(NameExpression $expression, ?string $variableDoesNotExistErrorMessage = NULL): void {
    $variableName = $this->getExpressionHandler()->getVariableNameFromNameExpression($expression);

    $this->compileAndMergeVariableToContext(
      VariableNameAndArrayKeysPair::createVariable($variableName),
      $variableDoesNotExistErrorMessage
    );
  }

  /**
   * Compiles & adds GetAttrExpression as a variable.
   *
   * To the Terrific Twig context.
   *
   * @param \Twig\Node\Expression\GetAttrExpression $expression
   *   The expression to compile.
   * @param string|null $variableDoesNotExistErrorMessage
   *   Custom error message that is used as exception message
   *   when the given variable does not exist.
   */
  public function compileAndMergeGetAttrExpressionToContext(GetAttrExpression $expression, ?string $variableDoesNotExistErrorMessage = NULL): void {
    $variableAndArrayKeys = $this->getExpressionHandler()->buildGetAttrExpressionArrayKeyPair($expression);

    $this->compileAndMergeVariableToContext($variableAndArrayKeys, $variableDoesNotExistErrorMessage);
  }

  /**
   * Checks if given variable exists, and adds it to the context.
   *
   * So it its data is available in the compiled component.
   *
   * @param VariableNameAndArrayKeysPair $variableNameAndArrayKeysPair
   *   Object containing the variable name and optionally array keys.
   * @param string|null $variableDoesNotExistErrorMessage
   *   Custom error message that is used as exception message
   *   when the given variable does not exist.
   * @throws Error
   */
  public function compileAndMergeVariableToContext(VariableNameAndArrayKeysPair $variableNameAndArrayKeysPair, ?string $variableDoesNotExistErrorMessage = NULL): void {
    $twigCompiler = $this->getTwigCompiler();

    $contextArrayKeys = $variableNameAndArrayKeysPair->toTwigContextArrayKeysString();

    if ($variableDoesNotExistErrorMessage === NULL) {
      $variableDoesNotExistErrorMessage = addslashes('The variable ' . $variableNameAndArrayKeysPair->toTwigVariableString() . ' does not exist. Could not compile it to the Twig context.');
    }

    $twigCompiler
      ->raw("\n")->write('')
      ->raw('if (')
      ->raw('isset(');

    $this->compileAsContextVariable($variableNameAndArrayKeysPair, '$context');

    $twigCompiler
      ->raw(')')
      ->raw(') {')
      ->raw("\n")->write('')->write('')
      ->raw(ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ' = array_merge(' . ContextProviderInterface::TERRIFIC_CONTEXT_VARIABLE . ', ');

    $this->compileAsContextVariable($variableNameAndArrayKeysPair, '$context');

    $twigCompiler
      ->raw(');')
      ->raw("\n")->write('')
      ->raw('} else {')
      ->raw("\n")->write('')->write('')
      ->raw('throw new \Twig\Error\Error("')
      ->raw($variableDoesNotExistErrorMessage)
      ->raw('");')
      ->raw("\n")->write('')
      ->raw('}')
      ->raw("\n\n");
  }

  /**
   * Returns the expression handler.
   *
   * @return \Namics\Terrific\Twig\Utility\ExpressionHandler
   *   The expression handler.
   */
  public function getExpressionHandler(): ExpressionHandler {
    return $this->expressionHandler;
  }

  /**
   * Returns the base Twig compiler.
   *
   * @return \Twig\Compiler
   *   The Twig compiler.
   */
  public function getTwigCompiler(): Compiler {
    return $this->compiler;
  }

  /**
   * Concatenates the template file extension to a string.
   *
   * Example output:
   * @code
   * . '.twig'
   * @endcode
   */
  public function compileConcatFileExtensionString(): void {
    $this->getTwigCompiler()->raw(". '");
    $this->compileTemplateFileExtension();
    $this->getTwigCompiler()->raw("'");
  }

  /**
   * Compiles the template file extension.
   *
   * Example output:
   * @code
   * .twig
   * @endcode
   */
  private function compileTemplateFileExtension(): void {
    $this->getTwigCompiler()->raw(TerrificExtension::TEMPLATE_FILE_EXTENSION);
  }

}
