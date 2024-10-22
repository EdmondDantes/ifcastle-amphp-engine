<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Async\CancellationInterface;
use IfCastle\Async\ChannelInterface;
use IfCastle\Async\CoroutineInterface;
use IfCastle\Async\CoroutineSchedulerInterface;
use IfCastle\Async\FutureInterface;
use IfCastle\Async\QueueInterface;
use Revolt\EventLoop;
use function Amp\Future\await;
use function Amp\Future\awaitAll;
use function Amp\Future\awaitAnyN;
use function Amp\Future\awaitFirst;

final class CoroutineScheduler      implements CoroutineSchedulerInterface
{
    #[\Override]
    public function run(callable $coroutine): CoroutineInterface
    {
        EventLoop::defer($coroutine);
    }
    
    #[\Override]
    public function await(iterable $futures, ?CancellationInterface $cancellation = null): array
    {
        return await($futures, $cancellation);
    }
    
    #[\Override]
    public function awaitFirst(iterable $futures, ?CancellationInterface $cancellation = null): mixed
    {
        return awaitFirst($futures, $cancellation);
    }
    
    #[\Override]
    public function awaitFirstSuccessful(iterable               $futures,
                                                      ?CancellationInterface $cancellation = null
    ): mixed
    {
        return awaitFirst($futures, $cancellation);
    }
    
    #[\Override]
    public function awaitAll(iterable $futures, ?CancellationInterface $cancellation = null): array
    {
        return awaitAll($futures, $cancellation);
    }
    
    #[\Override] public function awaitAllSuccessful(iterable               $futures,
                                                    ?CancellationInterface $cancellation = null
    ): array
    {
        return awaitAny($futures, $cancellation);
    }
    
    #[\Override]
    public function createChannelPair(int $size = 0): array
    {
        // TODO: Implement createChannelPair() method.
    }
    
    #[\Override]
    public function createQueue(int $size = 0): QueueInterface
    {
        // TODO: Implement createQueue() method.
    }
    
    #[\Override]
    public function stopAllCoroutines(?\Throwable $exception = null): bool
    {
        // TODO: Implement stopAllCoroutines() method.
    }
    
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
    public function cancelInterval(int|string $timerId): void
    {
        EventLoop::cancel($timerId);
    }
}