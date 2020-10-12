<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Http\Session;
use Falgun\Http\RequestInterface;
use Falgun\Midlayer\LayersInterface;
use Falgun\Midlayer\MiddlewareInterface;

final class CheckCsrfTokenMiddleware implements MiddlewareInterface
{

    private const CSRF_KEY = 'csrf_token';

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param RequestInterface $request
     * @param LayersInterface $layers
     * @return mixed from inner layer (controller)
     * @throws InvalidCsrfTokenException
     */
    public function handle(RequestInterface $request, LayersInterface $layers)
    {
        if ($request->getMethod() === 'POST') {
            $token = $request->postDatas()->get(self::CSRF_KEY);

            if ($this->session->has(self::CSRF_KEY) &&
                $this->session->get(self::CSRF_KEY) === $token) {

                // Token matched with session
                return $layers->next($request);
            }

            throw new InvalidCsrfTokenException();
        } elseif ($this->session->has(self::CSRF_KEY) === false) {
            $this->session->set(self::CSRF_KEY, \bin2hex(\random_bytes(32)));
        }

        return $layers->next($request);
    }
}
