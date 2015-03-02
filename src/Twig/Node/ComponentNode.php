<?php

namespace Deniaz\Terrific\Twig\Node;

use \Twig_Node;
use \Twig_Node_Expression;
use \Twig_NodeOutputInterface;
use \Twig_Compiler;
use \Twig_Node_Expression_Constant;
use \stdClass;

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
class ComponentNode extends Twig_Node implements Twig_NodeOutputInterface
{
    private $config;

    /**
     * @param stdClass             $config      Terrific Config
     * @param Twig_Node_Expression $template    Template Node
     * @param string               $variant     Template Variant
     * @param array                $variables   Additional Variables to be injected
     * @param int                  $lineno      Line Number
     * @param null                 $tag         Twig Tag
     */
    public function __construct(stdClass $config, Twig_Node_Expression $template, $variant = null, $variables = null, $lineno, $tag = null)
    {
        $this->config = $config;

        parent::__construct(
            ['template' => $template],
            ['variant' => $variant, 'variables' => $variables],
            $lineno,
            $tag
        );
    }

    /**
     * {@inheritdoc}
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $this->addGetTemplate($compiler);

        $compiler->raw('->display(');

        $this->addTemplateArguments($compiler);

        $compiler->raw(");\n");
    }

    /**
     * Compiles the Template.
     *
     * @param Twig_Compiler $compiler
     */
    protected function addGetTemplate(Twig_Compiler $compiler)
    {
        $method = $this->getNode('template') instanceof Twig_Node_Expression_Constant ? 'loadTemplate' : 'resolveTemplate';
        $this->setComponentPath();

        $compiler
            ->write(sprintf('$this->env->%s(', $method))
            ->subcompile($this->getNode('template'))
            ->raw(')')
        ;
    }

    /**
     * Compiles the injected variables
     * @param Twig_Compiler $compiler
     */
    protected function addTemplateArguments(Twig_Compiler $compiler)
    {
        if (null === $this->getAttribute('variables')) {
            $compiler->raw('$context');
        } else {
            $compiler
                ->raw('array_merge($context, ')
                ->subcompile($this->getAttribute('variables'))
                ->raw(')');
        }
    }

    /**
     * Changes the Node's value to match the Terrific concept.
     */
    protected function setComponentPath()
    {
        $template = $this->getNode('template')->getAttribute('value');
        $variant = $this->getAttribute('variant') === null
            ? ''
            : '-' . $this->getAttribute('variant');

        $this
            ->getNode('template')
            ->setAttribute(
                'value',
                $template . '/' . strtolower($template) . strtolower($variant) . $this->config->micro->view_file_extension
            );
    }
}