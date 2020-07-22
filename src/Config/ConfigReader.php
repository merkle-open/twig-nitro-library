<?php

namespace Namics\Terrific\Config;

use DomainException;

/**
 * ConfigReader reads Terrific Nitro's config.json and parses essential information such as component paths and the view
 * file extension.
 *
 * Class ConfigReader.
 *
 * @package Namics\Terrific\Config
 */
final class ConfigReader {
  /**
     * @const string FILE_NAME The Configuration's Filename.
     */
  const FILE_NAME = 'config.json';

  /**
   * @var array
   */
  private $config = NULL;

  /**
   * ConfigReader constructor.
   *
   * @param string $terrificDir
   *   Path to Terrific's Frontend Directory.
   *
   * @throws DomainException Thrown if Configuration could not be loaded correctly.
   */
  public function __construct($terrificDir) {
    $path = $terrificDir . '/' . self::FILE_NAME;

    if (is_readable($path)) {
      try {
        $this->config = json_decode(file_get_contents($path), TRUE);

        if ($this->config === NULL && json_last_error() !== JSON_ERROR_NONE) {
          throw new DomainException('Terrific Config could not be parsed.');
        }
      }
      catch (DomainException $e) {
        throw $e;
      }
    }
  }

  /**
   * Returns the Configuration.
   *
   * @return array
   */
  public function read() {
    return $this->config;
  }

}
