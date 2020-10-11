<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests;

use Falgun\Midlayer\Layers;
use PHPUnit\Framework\TestCase;
use Falgun\Middlewares\Tests\Mocks\MockSession;
use Falgun\Middlewares\Tests\Mocks\MockRequest;
use Falgun\Middlewares\StartSessionMiddleware;

final class StartSessionTest extends TestCase
{

    public function testStartOnFirstVisit()
    {
        $session = new MockSession();
        $request = new MockRequest();
        $layer = new Layers([], function() {
            return true;
        });

        $middleware = new StartSessionMiddleware($session);

        $middleware->handle($request, $layer);

        $this->assertSame(true, $session->get('test-start'));
    }
}
