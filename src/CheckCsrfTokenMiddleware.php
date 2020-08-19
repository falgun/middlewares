<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Http\Session;
use Falgun\Midlayer\Layers;
use Falgun\Http\RequestInterface;

class CheckCsrfTokenMiddleware implements MiddlewareInterface
{

    const CSRF_KEY = 'csrf_token';

    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(RequestInterface $request, Layers $layer)
    {
        if ($request->getMethod() === 'POST') {
            $token = $request->postDatas()->get(self::CSRF_KEY);

            if ($this->session->has(self::CSRF_KEY) &&
                $this->session->get(self::CSRF_KEY) === $token) {

                // Token matched with session
                return $layer->next($request);
            }

            throw new InvalidCsrfTokenException();
        } elseif ($this->session->has(self::CSRF_KEY) === false) {
            $this->session->set(self::CSRF_KEY, \bin2hex(\random_bytes(32)));
        }

        return $layer->next($request);
    }
}
