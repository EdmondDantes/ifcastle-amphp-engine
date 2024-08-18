<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Application\CoroutineContextInterface;

final class CoroutineContextManager implements CoroutineContextInterface
{
    private static array $contexts = [];
    private static ?CoroutineContext $mainContext = null;
    
    private static function defineCurrentContext(): CoroutineContext
    {
        $currentFiber               = \Fiber::getCurrent();
        
        if($currentFiber === null) {
            
            if(self::$mainContext === null) {
                self::$mainContext  = new CoroutineContext();
            }
            
            return self::$mainContext;
        }
        
        $cid                        = spl_object_id($currentFiber);
        
        if(!isset(self::$contexts[$cid])) {
            self::$contexts[$cid]   = new self;
        }
        
        return self::$contexts[$cid];
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