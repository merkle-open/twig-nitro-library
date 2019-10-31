<?php

namespace Deniaz\Terrific\Twig\Data;

use Twig\Error\Error;

/**
 * A key pair of variable name and array keys for it.
 *
 * @package Deniaz\Terrific\Twig\Data
 */
class VariableNameAndArrayKeysPair {

  /**
   * The variable name.
   *
   * @var string
   */
  protected $variableName;

  /**
   * Array of array keys for given variableName variable.
   *
   * That lead to the value.
   *
   * @var string[]
   */
  protected $arrayKeys;

  /**
   * VariableNameAndArrayKeysPair constructor.
   *
   * @param string $variableName
   *   The variable name.
   * @param array $arrayKeys
   *   Array of array keys for variable.
   */
  protected function __construct(string $variableName, array $arrayKeys = []) {
    $this->variableName = $variableName;
    $this->arrayKeys = $arrayKeys;
  }

  /**
   * Instantiates a new object.
   *
   * @param string $variableName
   *   The variable name.
   * @param array $arrayKeys
   *   Array of array keys for variable.
   */
  public static function create(string $variableName, array $arrayKeys) {
    return new self($variableName, $arrayKeys);
  }

  /**
   * Instantiates a new object for a variable without array keys.
   *
   * @param string $variableName
   *   The variable name.
   */
  public static function createVariable(string $variableName) {
    return new self($variableName);
  }

  /**
   * Returns the variable name.
   *
   * @return string
   *   The variable name.
   */
  public function getVariableName(): string {
    return $this->variableName;
  }

  /**
   * Sets the variable name.
   *
   * @param string $variableName
   *   The variable name.
   */
  public function setVariableName(string $variableName): void {
    $this->variableName = $variableName;
  }

  /**
   * Returns the array keys.
   *
   * @return string[]
   *   The array keys.
   */
  public function getArrayKeys(): array {
    return $this->arrayKeys;
  }

  /**
   * Sets the array keys.
   *
   * @param string[] $arrayKeys
   *   The array keys.
   */
  public function setArrayKeys(array $arrayKeys): void {
    $this->arrayKeys = $arrayKeys;
  }

  /**
   * Generates Twig context array keys.
   *
   *  Of the variable the object represents inside the Twig context.
   *
   * @return string
   *   E.g. ['myVariable']['aKey']['anotherKey'].
   *   Which may be used to access the value
   *   of the variable from the Twig context when compiling:
   *   $context['myVariable']['aKey']['anotherKey'].
   */
  public function toTwigContextArrayKeysString(): string {
    if (!$this->isVariableNameEmpty()) {
      $string = $this->formatAsArrayKeyString($this->getVariableName());
    }
    else {
      throw new Error('Cannot generate valid Twig variable string, variable name is empty.');
    }

    if (!$this->isArrayKeysEmpty()) {
      foreach ($this->getArrayKeys() as $arrayKey) {
        $string .= $this->formatAsArrayKeyString($arrayKey);
      }
    }

    return $string;
  }

  /**
   * Generates a string representation.
   *
   *  Of the variable the object represents inside the Twig template.
   *
   * @return string
   *   E.g. myVariable['aKey']['anotherKey'].
   */
  public function toTwigVariableString(): string {
    if (!$this->isVariableNameEmpty()) {
      $string = $this->getVariableName();
    }
    else {
      throw new Error('Cannot generate valid Twig variable string, variable name is empty.');
    }

    if (!$this->isArrayKeysEmpty()) {
      foreach ($this->getArrayKeys() as $arrayKey) {
        $string .= $this->formatAsArrayKeyString($arrayKey);
      }
    }

    return $string;
  }

  /**
   * Formats given string as an array key string.
   *
   * @param string $arrayKey
   *   The array key.
   *
   * @return string
   *   Array key formatted as array key string.
   */
  protected function formatAsArrayKeyString(string $arrayKey): string {
    return '["' . $arrayKey . '"]';
  }

  /**
   * Checks if the variable name is empty.
   *
   * @return bool
   *   TRUE if empty.
   */
  protected function isVariableNameEmpty(): bool {
    return empty($this->getVariableName());
  }

  /**
   * Checks if the array keys are empty.
   *
   * @return bool
   *   TRUE if empty.
   */
  protected function isArrayKeysEmpty(): bool {
    return empty($this->getArrayKeys());
  }

}
