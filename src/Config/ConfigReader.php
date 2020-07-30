<?php

namespace Namics\Terrific\Config;

use DomainException;

/**
 * Class ConfigReader.
 *
 * Reads Terrific Nitro's config.json and parses essential information such as component paths and the view
 * file extension.
 *
 * @package Namics\Terrific\Config
 */
final class ConfigReader {

  /**
   * The Terrific Nitro's config file.
   *
   * @var string
   */
  const TERRIFIC_NITRO_FILE_NAME = 'config.json';

  /**
   * The Terrific Nitro's config.
   *
   * @var array
   */
  private $config = [];

  /**
   * ConfigReader constructor.
   *
   * @param string $terrificDir
   *   Path to Terrific's frontend directory.
   *
   * @throws DomainException Thrown if Configuration could not be loaded correctly.
   */
  public function __construct($terrificDir) {
    $path = $terrificDir . '/' . self::TERRIFIC_NITRO_FILE_NAME;
    $this->readConfig($path);
  }

  /**
   * Returns Terrific Nitro's config.
   *
   * @return array
   *   Terrific Nitro config.
   */
  public function getConfig(): array {
    return $this->config;
  }

  /**
   * Read Terrific's config.
   *
   * @param string $path
   *   The path to the config file.
   */
  protected function readConfig(string $path) {
    if (is_readable($path)) {
      try {
        $this->config = json_decode(file_get_contents($path), TRUE);

        if ($this->config === NULL && json_last_error() !== JSON_ERROR_NONE) {
          throw new DomainException('Terrific config could not be parsed.');
        }
      }
      catch (DomainException $e) {
        throw $e;
      }
    }
  }

}
