<?php

namespace Mosaic\Http\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\Components\Definition;
use Mosaic\Http\Adapters\Psr7\Request;
use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\Adapters\Psr7\ResponseFactory;
use Mosaic\Http\Request as RequestInterface;
use Mosaic\Http\Response as ResponseInterface;
use Mosaic\Http\ResponseFactory as ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Psr7Request;
use Psr\Http\Message\ResponseInterface as Psr7Response;
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
            RequestInterface::class => function () {
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
            },
            Psr7Request::class => function ($container) {
                return $container->make(RequestInterface::class)->toPsr7();
            },
            Psr7Response::class => function ($container) {
                return $container->make(ResponseInterface::class)->toPsr7();
            }
        ];
    }
}
