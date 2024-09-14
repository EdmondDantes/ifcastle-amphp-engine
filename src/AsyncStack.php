<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use Amp\Pipeline\ConcurrentIterator;
use Amp\TimeoutCancellation;
use Amp\TimeoutException;
use IfCastle\DesignPatterns\Pool\StackInterface;
use Amp\Pipeline\Queue;

final class AsyncStack              implements StackInterface
{
    private Queue $queue;
    private ConcurrentIterator $iterator;
    private int $size = 0;
    
    public function __construct(private readonly int $waitTimeout = 1, int $bufferSize = 1)
    {
        $this->queue                = new Queue($bufferSize);
        $this->iterator             = $this->queue->iterate();
    }
    
    #[\Override]
    public function pop(): object|null
    {
        try {
            $this->iterator->continue(new TimeoutCancellation($this->waitTimeout));
            --$this->size;
            
            if($this->size < 0) {
                $this->size         = 0;
            }
            
            return $this->iterator->getValue();
        } catch (TimeoutException) {
            return null;
        }
    }
    
    #[\Override]
    public function push(object $object): void
    {
        $this->queue->pushAsync($object);
        ++$this->size;
    }
    
    #[\Override]
    public function getSize(): int
    {
        return $this->size;
    }
    
    #[\Override]
    public function clear(): void
    {
        $this->iterator->dispose();
        
        if(false === $this->queue->isComplete()) {
            $this->queue->complete();
        }
    }
}