<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Midlayer\Layers;
use Falgun\Http\Session;
use Falgun\Http\RequestInterface;

final class StartSessionMiddleware implements MiddlewareInterface
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param RequestInterface $request
     * @param Layers $layer
     * @return mixed from inner layer (controller)
     */
    public function handle(RequestInterface $request, Layers $layer)
    {
        $this->session->start();

        $response = $layer->next($request);

        return $response;
    }
}
