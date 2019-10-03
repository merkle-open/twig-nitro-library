<?php

namespace Deniaz\Terrific\Provider;

use Twig_Compiler;
use Twig_Node;

/**
 * Interface to describe a Context Provider.
 *
 * Interface ContextProviderInterface.
 *
 * @package Deniaz\Terrific\Provider
 */
interface ContextProviderInterface {

  /**
   * Compiles the $tContext variable which is passed to the Twig Template.
   *
   * @param \Twig_Compiler $compiler
   * @param \Twig_Node $component
   * @param \Twig_Node $dataVariant
   * @param $only
   *
   * @return mixed
   */
  public function compile(Twig_Compiler $compiler, Twig_Node $component, Twig_Node $dataVariant, $only);

}
