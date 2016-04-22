<?php

namespace Deniaz\Terrific\Twig\Node;

use \Twig_Compiler;
use \Twig_Node;
use \Twig_NodeOutputInterface;
use \Twig_Node_Expression;
<<<<<<< HEAD

final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface {
=======

/**
 * Includes a Terrific Component.
 *
 * <pre>
 *   {% component 'Navigation' %}
 *   {% component 'Navigation' 'primary %}
 *   {% component 'Navigation' with {"active": "home"} %}
 *   {% component 'Navigation' 'primary with {"active": "home"} %}
 * </pre>
 *
 * Class ComponentNode
 * @package Deniaz\Terrific\Twig\Node
 */
final class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface
{

  /**
   * ComponentNode constructor.
   */
  public function __construct(Twig_Node_Expression $component, Twig_Node_Expression $data = null, $only = false, $lineno, $tag = null)
  {
      parent::__construct(
      [ 'component' => $component, 'data' => $data ],
      [ 'only' => (bool) $only ],
      $lineno,
      $tag
    );
  }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
>>>>>>> 78298b060d695c124e89fd7ea92a87e9f6326bd4

  /**
   * ComponentNode constructor.
   */
  public function __construct(Twig_Node_Expression $component, Twig_Node_Expression $data = null, $only = false, $lineno, $tag = null) {

<<<<<<< HEAD
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
=======
        $compiler->raw('->display(');
        $this->addTemplateArguments($compiler);
        $compiler->raw(");\n");
    }

    protected function addGetTemplate(Twig_Compiler $compiler)
    {
        $compiler
>>>>>>> 78298b060d695c124e89fd7ea92a87e9f6326bd4
      ->write('$this->loadTemplate(')
      ->subcompile($this->getNode('component'))
      ->raw(', ')
      ->repr($compiler->getFilename())
      ->raw(', ')
      ->repr($this->getLine())
      ->raw(')');
<<<<<<< HEAD
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

  protected function transformData(Twig_Node_Expression $node, Twig_Compiler $compiler)
  {
    if ($node instanceof \Twig_Node_Expression_Array) {
      $compiler->subcompile($node);
    } elseif ($node instanceof \Twig_Node_Expression_Constant) {
      $compiler->subcompile("array('title' => '{$node->getAttribute('value')}')");
    }
  }
}
=======
    }

    protected function addTemplateArguments(Twig_Compiler $compiler)
    {
        if (null === $this->getNode('data')) {
            $compiler->raw(false === $this->getAttribute('only') ? '$context' : '[]');
        } elseif (false === $this->getAttribute('only')) {
            $compiler
        ->raw('array_merge($context, ')
        ->subcompile($this->getNode('data'))
        ->raw(')');
        } else {
            $compiler->subcompile($this->getNode('data'));
        }
    }
}
>>>>>>> 78298b060d695c124e89fd7ea92a87e9f6326bd4
