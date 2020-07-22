<?php

namespace Namics\Terrific\Provider;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * Interface to describe a Context Provider.
 *
 * Interface ContextProviderInterface.
 *
 * @package Namics\Terrific\Provider
 */
interface ContextProviderInterface {

  /**
   * The variable name of the Terrific Twig context.
   *
   * @var string
   */
  public const TERRIFIC_CONTEXT_VARIABLE = '$tContext';

  /**
   * Compiles the $tContext variable which is passed to the Twig Template.
   *
   * @param \Twig\Compiler $compiler
   * @param \Twig\Node\Node $component
   * @param \Twig\Node\Node $dataVariant
   * @param $only
   *
   * @return mixed
   */
  public function compile(Compiler $compiler, Node $component, Node $dataVariant, $only);

}
