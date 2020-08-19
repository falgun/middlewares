<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Midlayer\Layers;
use Falgun\Http\Session;
use Falgun\Http\RequestInterface;

class StartSessionMiddleware implements MiddlewareInterface
{

    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(RequestInterface $request, Layers $layer)
    {
        $this->session->start();

        $response = $layer->next($request);

        return $response;
    }
}
