<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Http\Session;
use Falgun\Http\RequestInterface;
use Falgun\Midlayer\LayersInterface;
use Falgun\Midlayer\MiddlewareInterface;

final class StartSessionMiddleware implements MiddlewareInterface
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param RequestInterface $request
     * @param LayersInterface $layers
     * @return mixed from inner layer (controller)
     */
    public function handle(RequestInterface $request, LayersInterface $layers)
    {
        $this->session->start();

        $response = $layers->next($request);

        return $response;
    }
}
