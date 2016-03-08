<?php

namespace Mosaic\Http;

use Mosaic\Common\Components\AbstractComponent;
use Mosaic\Http\Definitions\DiactorosDefinition;

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
            new DiactorosDefinition()
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
