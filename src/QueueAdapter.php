<?php

declare(strict_types=1);

namespace IfCastle\Amphp;

use Amp\Pipeline\Queue;
use IfCastle\Async\CancellationInterface;
use IfCastle\Async\ConcurrentIteratorInterface;
use IfCastle\Async\FutureInterface;
use IfCastle\Async\QueueInterface;

final readonly class QueueAdapter implements QueueInterface
{
    public function __construct(public Queue $queue) {}

    #[\Override]
    public function isComplete(): bool
    {
        return $this->queue->isComplete();
    }

    #[\Override]
    public function isDisposed(): bool
    {
        return $this->queue->isDisposed();
    }

    #[\Override]
    public function complete(): void
    {
        if (false === $this->queue->isComplete()) {
            $this->queue->complete();
        }
    }

    #[\Override]
    public function error(\Throwable $reason): void
    {
        if (false === $this->queue->isComplete()) {
            $this->queue->error($reason);
        }
    }

    #[\Override]
    public function pushAsync(mixed $value, ?CancellationInterface $cancellation = null): void
    {
        $this->queue->pushAsync($value)->ignore();
    }

    #[\Override]
    public function pushWithPromise(mixed $value, ?CancellationInterface $cancellation = null): FutureInterface
    {
        return new FutureAdapter($this->queue->pushAsync($value));
    }

    #[\Override]
    public function push(mixed $value): void
    {
        $this->queue->push($value);
    }

    #[\Override]
    public function getIterator(): ConcurrentIteratorInterface
    {
        return new ConcurrentIteratorAdapter($this->queue->iterate());
    }
}
