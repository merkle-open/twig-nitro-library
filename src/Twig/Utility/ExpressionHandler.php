<?php

namespace Namics\Terrific\Twig\Utility;

use Namics\Terrific\Twig\Data\VariableNameAndArrayKeysPair;
use Twig\Node\Node;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Error\Error;

/**
 * Provides functionality to work with Twig expressions.
 *
 * @package Namics\Terrific\Twig\Utility
 */
class ExpressionHandler {

  /**
   * ExpressionHandler constructor.
   */
  protected function __construct() {}

  /**
   * Instantiates a new object.
   */
  public static function create(): self {
    return new self();
  }

  /**
   * Generates a variable name and array key pair.
   *
   * @param \Twig\Node\Node $expression
   *   The expression to get the pair for.
   *
   * @return \Namics\Terrific\Twig\Data\VariableNameAndArrayKeysPair
   *   The pair.
   */
  public function buildGetAttrExpressionArrayKeyPair(Node $expression): VariableNameAndArrayKeysPair {
    $arrayKeys = $this->buildGetAttrExpressionArrayKeys($expression);

    // Get the variable name, it's the first array item.
    $arrayKeys = array_reverse($arrayKeys);
    $variableName = array_pop($arrayKeys);
    // Bring array back into original order.
    $arrayKeys = array_reverse($arrayKeys);

    return VariableNameAndArrayKeysPair::create($variableName, $arrayKeys);
  }

  /**
   * Generates an array with the array keys.
   *
   * Pointing to the value location of given expression in the context.
   *
   * @param \Twig\Node\Node $expression
   *   The expression to get the context array keys for.
   *
   * @return string[]
   *   Array with array keys.
   */
  public function buildGetAttrExpressionArrayKeys(Node $expression): array {
    if ($this->isNestedObject($expression)) {
      $dataVariableName = $expression->getNode('attribute')->getAttribute('value');
      $childExpression = $expression->getNode('node');
      $dataVariableArrayKeys = $this->buildGetAttrExpressionArrayKeys($childExpression);

      $dataExpressionArrayKeys = $dataVariableArrayKeys;
      $dataExpressionArrayKeys[] = $dataVariableName;

      return $dataExpressionArrayKeys;
    }
    else {
      $dataVariableName = $expression->getNode('node')->getAttribute('name');
      $dataVariableArrayKey = $expression->getNode('attribute')->getAttribute('value');

      $dataExpressionArrayKeys = [$dataVariableName, $dataVariableArrayKey];

      return $dataExpressionArrayKeys;
    }
  }

  /**
   * Checks if given Twig expression is a nested object.
   *
   * @param \Twig\Node\Node $expression
   *   The Twig expression to check.
   *
   * @return bool
   *   TRUE if nested object.
   *   Otherwise FALSE.
   */
  public function isNestedObject(Node $expression): bool {
    $isNestedObject = $expression->hasNode('node') && $expression->getNode('node') instanceof GetAttrExpression;

    return $isNestedObject;
  }

  /**
   * Returns the context variable name from given expression.
   *
   * @param \Twig\Node\Expression\NameExpression $expression
   *   The Twig expression to check.
   *
   * @return string
   *   The variable name.
   *
   * @throws \Twig\Error\Error
   *   If the variable name value is not valid.
   */
  public function getVariableNameFromNameExpression(NameExpression $expression): string {
    $variableName = $expression->getAttribute('name');

    if (!empty($variableName) && is_string($variableName)) {
      return $variableName;
    }
    else {
      throw new Error(
        'The variable name from NameExpression could not be read inside Twig node "' . $expression->getTemplateName() . '".',
        $expression->getTemplateLine()
      );
    }
  }

}
