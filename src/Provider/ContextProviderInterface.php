<?php

namespace Deniaz\Terrific\Provider;

use \Twig_Compiler;
use \Twig_Node;

interface ContextProviderInterface {
    public function compile(Twig_Compiler $compiler, Twig_Node $component, Twig_Node $dataVariant, $only);
}