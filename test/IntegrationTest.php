<?php

namespace Deniaz\Test\Terrific;

use Deniaz\Terrific\Twig\Extension\ComponentExtension;

class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    protected function getExtensions()
    {
        return [
            new ComponentExtension()
        ];
    }

    protected function getFixturesDir()
    {
        return dirname(__DIR__) . '/Fixtures/';
    }

}