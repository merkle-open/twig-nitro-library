<?php

namespace Namics\Terrific\Twig\Node;

use Twig\Node\Node;
use Twig\Compiler;

/**
 * PlaceholderNode represents a placeholder node.
 *
 * Class PlaceholderNode.
 *
 * @package Namics\Terrific\Twig\Node
 */
class PlaceholderNode extends Node
{

  /**
   * PlaceholderNode constructor.
   *
   * @param array $attributes
   *   An associative array of attribute names and their values.
   * @param array $nodes
   *   An array of child nodes (unused here but part of the Node structure).
   * @param int $lineno
   *   Line Number.
   * @param string $tag
   *   Tag name associated with the node.
   */
  public function __construct(array $attributes = [], array $nodes = [], int $lineno = 0, string $tag = null)
  {
    parent::__construct($attributes, $nodes, $lineno, $tag);
  }

  /**
   * Compiles the placeholder.
   *
   * @param \Twig\Compiler $compiler
   *   The Twig compiler.
   */
  public function compile(Compiler $compiler): void
  {
    // No output is needed for the placeholder tag
    $compiler->addDebugInfo($this);
    // This can be empty since we don't need to output anything
  }
}
