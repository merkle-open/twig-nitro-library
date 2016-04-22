<?php

namespace Deniaz\Terrific\Config;

final class ConfigReader {

  const FILE_NAME = 'config.json';

  private $config = null;
  /**
   * ConfigFactory constructor.
   */
  public function __construct($terrificDir) {
    $path = $terrificDir . '/' . self::FILE_NAME;

    if (is_readable($path)) {
      $this->config = json_decode(file_get_contents($path), true);

      if ($this->config === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new \DomainException("Terrific Config could not be parsed.");
      }
    }
  }

  public function read() {
    return $this->config;
  }
}