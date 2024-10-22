<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Async\CoroutineInterface;

class CoroutineAdapter implements CoroutineInterface
{
    public function __construct(private string $id)
    {
    }
    
    #[\Override]
    public function getCoroutineId(): int|string
    {
        return $this->id;
    }
    
    #[\Override]
    public function isRunning(): bool
    {
    
    }
    
    #[\Override] public function isCancelled(): bool
    {
        // TODO: Implement isCancelled() method.
    }
    
    #[\Override] public function isFinished(): bool
    {
        // TODO: Implement isFinished() method.
    }
    
    #[\Override] public function stop(\Throwable $throwable = null): bool
    {
        // TODO: Implement stop() method.
    }
}