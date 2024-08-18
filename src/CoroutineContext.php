<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

final class CoroutineContext extends \ArrayObject
{
    private array $callbacks        = [];
    
    public function defer(callable $callback): void
    {
        $this->callbacks[]          = $callback;
    }
    
    public function __destruct()
    {
        foreach($this->callbacks as $callback) {
            $callback();
        }
    }
}