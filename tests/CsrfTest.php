<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests;

use Falgun\Midlayer\Layers;
use PHPUnit\Framework\TestCase;
use Falgun\Middlewares\Tests\Mocks\MockSession;
use Falgun\Middlewares\Tests\Mocks\MockRequest;
use Falgun\Middlewares\CheckCsrfTokenMiddleware;
use Falgun\Middlewares\InvalidCsrfTokenException;

final class CsrfTest extends TestCase
{

    public function testInvalidCsrf()
    {
        $session = new MockSession();
        $session->set('csrf_token', '1234567');
        $request = new MockRequest();
        $layer = new Layers([], function() {
            return true;
        });

        $csrfMiddleware = new CheckCsrfTokenMiddleware($session);

        $this->expectException(InvalidCsrfTokenException::class);
        $csrfMiddleware->handle($request, $layer);
    }

    public function testValidCsrf()
    {
        $session = new MockSession();
        $session->set('csrf_token', '1234567890');
        $request = new MockRequest();
        $layer = new Layers([], function() {
            return true;
        });

        $csrfMiddleware = new CheckCsrfTokenMiddleware($session);

        $response = $csrfMiddleware->handle($request, $layer);
        $this->assertSame(true, $response);
    }

    public function testCsrfSetup()
    {
        $session = new MockSession();
        $request = new MockRequest();
        $request->setMethod('GET');

        $layer = new Layers([], function() {
            return true;
        });

        $csrfMiddleware = new CheckCsrfTokenMiddleware($session);

        $csrfMiddleware->handle($request, $layer);

        $this->assertSame(true, $session->has('csrf_token'));
        $this->assertSame(64, \strlen($session->get('csrf_token')));
    }
}
