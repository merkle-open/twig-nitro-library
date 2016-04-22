<?php

namespace Deniaz\Terrific\Twig\Node;

use \Twig_Compiler;
use \Twig_Node;
use \Twig_NodeOutputInterface;
use \Twig_Node_Expression;

final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface {

  /**
   * ComponentNode constructor.
   */
  public function __construct(Twig_Node_Expression $component, Twig_Node_Expression $data = null, $only = false, $lineno, $tag = null) {

    parent::__construct(
      [ 'component' => $component, 'data' => $data ],
      [ 'only' => (bool) $only ],
      $lineno,
      $tag
    );
  }

  public function compile(Twig_Compiler $compiler) {
    $compiler->addDebugInfo($this);

    $this->addGetTemplate($compiler);

    $compiler->raw('->display(');
    $this->addTemplateArguments($compiler);
    $compiler->raw(");\n");
  }

  protected function addGetTemplate(Twig_Compiler $compiler) {
    $compiler
      ->write('$this->loadTemplate(')
      ->subcompile($this->getNode('component'))
      ->raw(', ')
      ->repr($compiler->getFilename())
      ->raw(', ')
      ->repr($this->getLine())
      ->raw(')');
  }

  protected function addTemplateArguments(Twig_Compiler $compiler) {
    $data = $this->getNode('data');
    if (null === $data) {
      $compiler->raw(false === $this->getAttribute('only') ? '$context' : '[]');
    } elseif (false === $this->getAttribute('only')) {
      $compiler->raw('array_merge($context, ');
      $this->transformData($data, $compiler);
      $compiler->raw(')');
    } else {
      $compiler->subcompile($this->getNode('data'));
    }
  }

  /**
   * @param \Twig_Node_Expression $node
   * @param \Twig_Compiler $compiler
   * @TODO Actually do something about the second string param.
   */
  protected function transformData(Twig_Node_Expression $node, Twig_Compiler $compiler)
  {
    if ($node instanceof \Twig_Node_Expression_Array) {
      $compiler->subcompile($node);
    } elseif ($node instanceof \Twig_Node_Expression_Constant) {
      $compiler->subcompile(
        new \Twig_Node_Expression_Array([
          new \Twig_Node_Expression_Constant('data_source', $this->getLine()),
          new \Twig_Node_Expression_Constant($node->getAttribute('value'), $this->getLine())
        ], $this->getLine())
      );
    }
  }
}
