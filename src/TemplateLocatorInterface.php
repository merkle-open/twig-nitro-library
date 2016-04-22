<?php

namespace Deniaz\Terrific;

interface TemplateLocatorInterface {
  public function getPaths();

  public function getFileExtension();
}