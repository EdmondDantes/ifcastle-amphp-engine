<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Amphp\Internal\Coroutine;
use IfCastle\Amphp\Internal\Scheduler;
use IfCastle\Async\CancellationInterface;
use IfCastle\Async\CoroutineInterface;
use IfCastle\Async\CoroutineSchedulerInterface;
use IfCastle\Async\QueueInterface;
use Revolt\EventLoop;
use function Amp\Future\await;
use function Amp\Future\awaitAll;
use function Amp\Future\awaitAny;
use function Amp\Future\awaitFirst;

final class CoroutineScheduler      implements CoroutineSchedulerInterface
{
    #[\Override]
    public function run(\Closure $function): CoroutineInterface
    {
        return new CoroutineAdapter(Scheduler::default()->run(new Coroutine($function)));
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
        Scheduler::default()->stopAll($exception);
        return true;
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