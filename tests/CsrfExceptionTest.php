<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Middlewares\InvalidCsrfTokenException;

final class CsrfExceptionTest extends TestCase
{

    public function testCsrfException()
    {
        $exception = new InvalidCsrfTokenException();

        $this->assertSame(403, $exception->getCode());
        $this->assertSame('Invalid CSRF Token provided!', $exception->getMessage());
    }
}
