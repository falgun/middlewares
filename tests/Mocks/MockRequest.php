<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests\Mocks;

final class MockRequest implements \Falgun\Http\RequestInterface
{

    private $method = 'POST';

    public function attributes(): \Falgun\Http\Parameters\Attributes
    {
        return new \Falgun\Http\Parameters\Attributes();
    }

    public function cookies(): \Falgun\Http\Parameters\Cookies
    {
        return new \Falgun\Http\Parameters\Cookies();
    }

    public function files(): \Falgun\Http\Parameters\Files
    {
        return new \Falgun\Http\Parameters\Files([]);
    }

    public function getBody(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function headers(): \Falgun\Http\Parameters\Headers
    {
        return new \Falgun\Http\Parameters\Headers();
    }

    public function postDatas(): \Falgun\Http\Parameters\PostDatas
    {
        return new \Falgun\Http\Parameters\PostDatas(['csrf_token' => '1234567890']);
    }

    public function queryDatas(): \Falgun\Http\Parameters\QueryDatas
    {
        return new \Falgun\Http\Parameters\QueryDatas();
    }

    public function serverDatas(): \Falgun\Http\Parameters\ServerDatas
    {
        return new \Falgun\Http\Parameters\ServerDatas();
    }

    public function setBody(string $body): \Falgun\Http\RequestInterface
    {
        return $this;
    }

    public function setMethod(string $method): \Falgun\Http\RequestInterface
    {
        $this->method = $method;

        return $this;
    }

    public function uri(): \Falgun\Http\Uri
    {
        return \Falgun\Http\Uri::fromString('http://localhost');
    }
}
