<?php

declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Async\CoroutineContextInterface;
use Revolt\EventLoop;

final class CoroutineContext implements CoroutineContextInterface
{
    private static EventLoop\FiberLocal $fiberLocal;

    private static function defineCurrentContext(): InternalContext
    {
        if (empty(self::$fiberLocal)) {
            self::$fiberLocal       = new EventLoop\FiberLocal(fn() => new InternalContext());
        }

        return self::$fiberLocal->get();
    }

    public function isCoroutine(): bool
    {
        return \Fiber::getCurrent() !== null;
    }

    public function getCoroutineId(): string|int
    {
        $currentFiber               = \Fiber::getCurrent();

        if ($currentFiber === null) {
            return -1;
        }

        return \spl_object_id($currentFiber);
    }

    public function getCoroutineParentId(): string|int
    {
        return -1;
    }

    public function has(string $key): bool
    {
        return self::defineCurrentContext()->offsetExists($key);
    }

    public function get(string $key): mixed
    {
        $context                    = self::defineCurrentContext();

        if (!$context->offsetExists($key)) {
            return null;
        }

        return $context->offsetGet($key);
    }

    public function getLocal(string $key): mixed
    {
        return $this->get($key);
    }

    public function hasLocal(string $key): bool
    {
        return $this->has($key);
    }

    public function set(string $key, mixed $value): static
    {
        self::defineCurrentContext()->offsetSet($key, $value);

        return $this;
    }

    public function defer(callable $callback): static
    {
        self::defineCurrentContext()->defer($callback);

        return $this;
    }
}
