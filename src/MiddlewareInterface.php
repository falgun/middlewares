<?php

namespace Falgun\Middlewares;

use Falgun\Midlayer\Layers;
use Falgun\Http\RequestInterface;

interface MiddlewareInterface
{

    /**
     * @param RequestInterface $request
     * @param Layers $layer
     * @return mixed from inner layer (controller)
     */
    public function handle(RequestInterface $request, Layers $layer);
}
