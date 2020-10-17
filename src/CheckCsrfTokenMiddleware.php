<?php
declare(strict_types=1);

namespace Falgun\Middlewares;

use Falgun\Http\Session;
use Falgun\Csrf\CsrfToken;
use Falgun\Csrf\CsrfTokenManager;
use Falgun\Http\RequestInterface;
use Falgun\Midlayer\LayersInterface;
use Falgun\Csrf\Storage\SessionStorage;
use Falgun\Midlayer\MiddlewareInterface;
use Falgun\Csrf\Mechanisms\BasicHashMechanism;

final class CheckCsrfTokenMiddleware implements MiddlewareInterface
{

    private const CSRF_KEY = '_token';

    private CsrfTokenManager $tokenManager;

    public function __construct(SessionStorage $storage, BasicHashMechanism $mechanism)
    {
        $this->tokenManager = new CsrfTokenManager($storage, $mechanism);
    }

    /**
     * @param RequestInterface $request
     * @param LayersInterface $layers
     * @return mixed from inner layer (controller)
     * @throws InvalidCsrfTokenException
     */
    public function handle(RequestInterface $request, LayersInterface $layers)
    {
        if ($this->isInReadMode($request) === false) {
            $token = $this->getRequestToken($request);

            if ($this->tokenManager->isValid($token)) {

                return $layers->next($request);
            }

            throw new InvalidCsrfTokenException();
        }

        // this will set token if not already set
        $this->tokenManager->getOrGenerate(self::CSRF_KEY);

        return $layers->next($request);
    }

    private function getRequestToken(RequestInterface $request): CsrfToken
    {
        /**
         * @todo implement fetch from header
         */
        $token = $request->postDatas()->get(self::CSRF_KEY, '');

        return CsrfToken::new(self::CSRF_KEY, $token);
    }

    private function isInReadMode(RequestInterface $request): bool
    {
        switch ($request->getMethod()):
            case 'HEAD':
                return true;
            case 'GET':
                return true;
            case 'OPTIONS':
                return true;
        endswitch;

        return false;
    }
}
