<?php
declare(strict_types=1);

namespace Falgun\Middlewares\Tests\Mocks;

use Falgun\Http\Session;

class MockSession extends Session
{

    protected array $sessions;

    public function __construct()
    {
        parent::__construct();
        $this->sessions = [];
    }

    public function start(array $options = []): bool
    {
        $this->set('test-start', true);
        return true;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->sessions);
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->sessions[$key];
        }

        throw new \Exception('Session not found for key: ' . $key);
    }

    public function set(string $key, $value): self
    {
        $this->sessions[$key] = $value;

        return $this;
    }

    public function remove(string $key): self
    {
        unset($this->sessions[$key]);

        return $this;
    }
}
