<?php

namespace Mosaic\Http\Tests;

use Mosaic\Http\Component;
use Mosaic\Http\Providers\DiactorosProvider;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    public function test_can_resolve_diactoros()
    {
        $component = Component::diactoros();

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('diactoros', $component->getImplementation());
        $this->assertEquals([new DiactorosProvider()], $component->getProviders());
    }

    public function test_can_resolve_custom()
    {
        Component::extend('customHttp', function () {
            return [
                new DiactorosProvider()
            ];
        });

        $component = Component::customHttp();

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('customHttp', $component->getImplementation());
        $this->assertEquals([new DiactorosProvider()], $component->getProviders());
    }
}
