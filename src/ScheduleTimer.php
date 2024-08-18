<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Application\ScheduleTimerInterface;
use Revolt\EventLoop;

final class ScheduleTimer           implements ScheduleTimerInterface
{
    #[\Override]
    public function defer(callable $callback): void
    {
        EventLoop::defer($callback);
    }
    
    #[\Override]
    public function delay(float|int $delay, callable $callback): int|string
    {
        return EventLoop::delay($delay, $callback);
    }
    
    #[\Override]
    public function interval(float|int $interval, callable $callback): int|string
    {
        return EventLoop::repeat($interval, $callback);
    }
    
    #[\Override]
    public function clear(int|string $timerId): void
    {
        EventLoop::cancel($timerId);
    }
}