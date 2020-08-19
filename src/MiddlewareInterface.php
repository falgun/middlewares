<?php

namespace Falgun\Middlewares;

use Falgun\Midlayer\Layers;
use Falgun\Http\RequestInterface;

interface MiddlewareInterface
{

    public function handle(RequestInterface $request, Layers $layer);
}
