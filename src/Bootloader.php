<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Application\Bootloader\BootloaderExecutorInterface;
use IfCastle\Application\Bootloader\BootloaderInterface;
use IfCastle\Application\CoroutineContextInterface;
use IfCastle\Application\EngineInterface;
use IfCastle\Application\ScheduleTimerInterface;

final class Bootloader implements BootloaderInterface
{
    #[\Override]
    public function buildBootloader(BootloaderExecutorInterface $bootloaderExecutor): void
    {
        $builder                    = $bootloaderExecutor->getBootloaderContext()->getSystemEnvironmentBootBuilder();
        
        $builder->bindConstructible(EngineInterface::class, AmphpEngine::class)
                ->bindConstructible(CoroutineContextInterface::class, CoroutineContext::class)
                ->bindConstructible(ScheduleTimerInterface::class, ScheduleTimer::class);
    }
}