<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests;

use Falgun\Midlayer\Layers;
use PHPUnit\Framework\TestCase;
use Falgun\Middlewares\Tests\Mocks\MockSession;
use Falgun\Middlewares\Tests\Mocks\MockRequest;
use Falgun\Middlewares\CheckCsrfTokenMiddleware;
use Falgun\Middlewares\InvalidCsrfTokenException;
use Falgun\Csrf\Storage\SessionStorage;
use Falgun\Csrf\Mechanisms\BasicHashMechanism;

final class CsrfTest extends TestCase
{

    private function getDummyLayer(): Layers
    {
        return new Layers(
            [],
            function() {
            return true;
        },
            function() {
            
        }
        );
    }

    public function testInvalidCsrf()
    {
        $session = new MockSession();
        $request = new MockRequest();
        $layer = $this->getDummyLayer();

        $storage = new SessionStorage($session);
        $mechanism = new BasicHashMechanism();

        $storage->set('_token', '1234567');

        $csrfMiddleware = new CheckCsrfTokenMiddleware($storage, $mechanism);

        $this->expectException(InvalidCsrfTokenException::class);
        $csrfMiddleware->handle($request, $layer);
    }

    public function testValidCsrf()
    {
        $session = new MockSession();

        $request = new MockRequest();
        $layer = $this->getDummyLayer();

        $storage = new SessionStorage($session);
        $mechanism = new BasicHashMechanism();

        $storage->set('_token', '1234567890');

        $csrfMiddleware = new CheckCsrfTokenMiddleware($storage, $mechanism);

        $response = $csrfMiddleware->handle($request, $layer);
        $this->assertSame(true, $response);
    }

    public function testCsrfSetup()
    {
        $session = new MockSession();
        $request = new MockRequest();
        $request->setMethod('GET');

        $layer = $this->getDummyLayer();

        $storage = new SessionStorage($session);
        $mechanism = new BasicHashMechanism();

        $csrfMiddleware = new CheckCsrfTokenMiddleware($storage, $mechanism);

        $csrfMiddleware->handle($request, $layer);

        $this->assertSame(true, $session->has('_token'));
    }

    public function testCsrfCheckOnGetMethod()
    {
        $session = new MockSession();
        $request = new MockRequest();
        $request->setMethod('GET');

        $layer = $this->getDummyLayer();

        $storage = new SessionStorage($session);
        $mechanism = new BasicHashMechanism();

        $storage->set('_token', 'invalid');

        $csrfMiddleware = new CheckCsrfTokenMiddleware($storage, $mechanism);

        $response1 = $csrfMiddleware->handle($request, $layer);
        $this->assertTrue($response1);
        
        
        $request->setMethod('HEAD');
        $response2 = $csrfMiddleware->handle($request, $layer);
        $this->assertTrue($response2);
        
        $request->setMethod('OPTIONS');
        $response3 = $csrfMiddleware->handle($request, $layer);
        $this->assertTrue($response3);
    }
}
