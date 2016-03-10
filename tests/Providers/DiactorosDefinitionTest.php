<?php

namespace Mosaic\Http\Tests\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Http\Providers\DiactorosProvider;
use Mosaic\Http\Request;
use Mosaic\Http\Response;
use Mosaic\Http\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DiactorosProviderTest extends \PHPUnit_Framework_TestCase
{
    public function getDefinition() : DefinitionProviderInterface
    {
        return new DiactorosProvider();
    }

    public function shouldDefine() : array
    {
        return [
            Request::class,
            Response::class,
            ResponseFactory::class,
            ServerRequestInterface::class,
            ResponseInterface::class
        ];
    }

    public function test_defines_all_required_contracts()
    {
        $definitions = $this->getDefinition()->getDefinitions();
        foreach ($this->shouldDefine() as $define) {
            $this->assertArrayHasKey($define, $definitions);
        }
    }
}
