<?php

declare(strict_types=1);

namespace Namics\Test\Terrific\Twig;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TwigExtension\TerrificExtension;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use Namics\Terrific\Twig\TokenParser\PlaceholderTokenParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TwigTestBase.
 *
 * @package Namics\Test\Terrific\Twig
 */
abstract class TwigTestBase extends TestCase {

  /**
   * Get context provider mock.
   *
   * @return \Namics\Terrific\Provider\ContextProviderInterface
   *   The context provider mock object.
   */
  protected function getContextProviderMock(): ContextProviderInterface {
    return $this->getMockBuilder(ContextProviderInterface::class)->getMock();;
  }

  /**
   * Get the Terrific Twig extension.
   *
   * @return \Namics\Terrific\Twig\TwigExtension\TerrificExtension
   *   The Terrific Twig extension.
   */
  protected function getTwigExtension(): TerrificExtension {
    return new TerrificExtension($this->getContextProviderMock());
  }

  /**
   * Get the component token parser.
   *
   * @return \Namics\Terrific\Twig\TokenParser\ComponentTokenParser
   *   The component token parser.
   */
  protected function getComponentTokenParser(): ComponentTokenParser {
    return new ComponentTokenParser($this->getContextProviderMock());
  }

  /**
   * Get the placeholder token parser.
   *
   * @return \Namics\Terrific\Twig\TokenParser\PlaceholderTokenParser
   *   The placeholder token parser.
   */
  protected function getPlaceholderTokenParser(): PlaceholderTokenParser {
    return new PlaceholderTokenParser();
  }
}
