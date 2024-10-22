<?php
declare(strict_types=1);

namespace IfCastle\Amphp\Internal\Exceptions;

use IfCastle\Amphp\Internal\Coroutine;
use IfCastle\Exceptions\RuntimeException;

class CoroutineTerminationException extends RuntimeException
{
    public function __construct(
        string $message,
        Coroutine $coroutine,
        \Throwable                                     $previous = null
    )
    {
        parent::__construct([
            
            'message'               => $message,
        ],
        0, $previous);
    }
}
