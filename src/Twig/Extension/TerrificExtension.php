<?php

namespace Namics\Terrific\Twig\Extension;

use Namics\Terrific\Provider\ContextProviderInterface;
use Namics\Terrific\Twig\TokenParser\ComponentTokenParser;
use Twig\Extension\AbstractExtension;

/**
 * TerrificExtension adds Terrific Features to the Twig Environment. Currently only the ComponentTokenParser is added,
 * which results in the additional component tag.
 *
 * Class TerrificExtension.
 *
 * @package Namics\Terrific\Twig\Extension
 */
final class TerrificExtension extends AbstractExtension {

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
   *
   * @TODO: Default Provider?
   */
  public function __construct(ContextProviderInterface $ctxProvider) {
    $this->ctxProvider = $ctxProvider;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'terrific';
  }

  /**
   * {@inheritdoc}
   */
  public function getTokenParsers() {
    return [
      new ComponentTokenParser($this->ctxProvider),
    ];
  }

}
