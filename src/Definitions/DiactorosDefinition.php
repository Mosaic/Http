<?php

namespace Mosaic\Http\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Foundation\Components\Definition;
use Mosaic\Http\Adapters\Psr7\Request;
use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\Adapters\Psr7\ResponseFactory;
use Mosaic\Http\Request as RequestInterface;
use Mosaic\Http\Response as ResponseInterface;
use Mosaic\Http\ResponseFactory as ResponseFactoryInterface;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\ServerRequestFactory;

class DiactorosDefinition implements DefinitionProviderInterface
{
    /**
     * @return array|Definition[]
     */
    public function getDefinitions() : array
    {
        return [
            RequestInterface::class  => function () {
                return new Request(
                    ServerRequestFactory::fromGlobals()
                );
            },
            ResponseInterface::class => function () {
                return new Response(
                    new DiactorosResponse
                );
            },
            ResponseFactoryInterface::class => function () {
                return new ResponseFactory;
            }
        ];
    }
}
