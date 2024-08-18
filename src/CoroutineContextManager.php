<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Application\CoroutineContextInterface;
use Revolt\EventLoop;

final class CoroutineContextManager implements CoroutineContextInterface
{
    private static array $contexts = [];
    private static array $fibers   = [];
    private static ?CoroutineContext $mainContext = null;
    
    private static function defineCurrentContext(): CoroutineContext
    {
        self::tryGarbageCollector();
        
        $currentFiber               = \Fiber::getCurrent();
        
        if($currentFiber === null) {
            
            if(self::$mainContext === null) {
                self::$mainContext  = new CoroutineContext();
            }
            
            return self::$mainContext;
        }
        
        $cid                        = spl_object_id($currentFiber);
        
        if(!isset(self::$contexts[$cid])) {
            self::$contexts[$cid]   = new CoroutineContext();
            self::$fibers[$cid]     = $currentFiber;
        }
        
        return self::$contexts[$cid];
    }
    
    private static function tryGarbageCollector(): void
    {
        foreach (self::$fibers as $fiber) {
            if ($fiber->isTerminated()) {
                EventLoop::defer(self::disposeContexts(...));
                return;
            }
        }
    }
    
    public static function disposeContexts(): void
    {
        foreach (self::$fibers as $cid => $fiber) {
            if (false === $fiber->isTerminated()) {
                continue;
            }
            
            unset(self::$fibers[$cid]);
            
            $context                = self::$contexts[$cid] ?? null;
            
            if($context !== null) {
                unset(self::$contexts[$cid]);
                $context->dispose();
            }
        }
    }
    
    public function isCoroutine(): bool
    {
        return \Fiber::getCurrent() !== null;
    }
    
    public function getCoroutineId(): string|int
    {
        $currentFiber               = \Fiber::getCurrent();
        
        if($currentFiber === null) {
            return -1;
        }
        
        return spl_object_id($currentFiber);
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
        
        if(!$context->offsetExists($key)) {
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