<?php

namespace Mosaic\Http;

use Mosaic\Common\Components\AbstractComponent;
use Mosaic\Http\Providers\DiactorosProvider;

/**
 * @method static $this diactoros()
 */
final class Component extends AbstractComponent
{
    /**
     * @return array
     */
    public function resolveDiactoros()
    {
        return [
            new DiactorosProvider()
        ];
    }

    /**
     * @param  callable $callback
     * @return array
     */
    public function resolveCustom(callable $callback) : array
    {
        return $callback();
    }
}
