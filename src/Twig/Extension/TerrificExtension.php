<?php

namespace Namics\Terrific\Twig\Extension;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use Twig\Extension\AbstractExtension;

/**
 * Adds Terrific features to the Twig Environment.
 *
 * @package Namics\Terrific\Twig\Extension
 */
final class TerrificExtension extends AbstractExtension {

  /**
   * The file extension of templates that this extension loads.
   *
   * @var string
   */
  public const TEMPLATE_FILE_EXTENSION = '.twig';

  /**
   * The context provider.
   *
   * @var \Namics\Terrific\Provider\ContextProviderInterface
   */
  private $ctxProvider;

  /**
   * TerrificExtension constructor.
   *
   * @param \Namics\Terrific\Provider\ContextProviderInterface $ctxProvider
   *   The context provider.
   */
  public function __construct(ContextProviderInterface $ctxProvider) {
    $this->ctxProvider = $ctxProvider;
  }

  /**
   * Returns the extension name.
   *
   * @return string
   *   Twig extension name.
   */
  public function getName(): string {
    return 'terrific';
  }

  /**
   * Returns the token parsers of this extension.
   *
   * @return \Twig\TokenParser\TokenParserInterface[]
   *   The token parsers of this extension.
   */
  public function getTokenParsers(): array {
    return [
      new ComponentTokenParser($this->ctxProvider),
    ];
  }

}
