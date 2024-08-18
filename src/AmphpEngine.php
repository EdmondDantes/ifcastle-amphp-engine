<?php
declare(strict_types=1);

namespace IfCastle\Amphp;

use IfCastle\Application\EngineInterface;
use IfCastle\Application\EngineRolesEnum;

class AmphpEngine implements EngineInterface
{
    #[\Override]
    public function start(): void
    {
    }
    
    #[\Override]
    public function getEngineName(): string
    {
        return 'amphp/'.phpversion();
    }
    
    #[\Override]
    public function getEngineRole(): EngineRolesEnum
    {
        return EngineRolesEnum::PROCESS;
    }
    
    #[\Override]
    public function isServer(): bool
    {
        return false;
    }
    
    #[\Override]
    public function isProcess(): bool
    {
        return true;
    }
    
    #[\Override]
    public function isConsole(): bool
    {
        return false;
    }
    
    #[\Override]
    public function isStateful(): bool
    {
        return true;
    }
    
    #[\Override]
    public function isAsynchronous(): bool
    {
        return true;
    }
    
    #[\Override]
    public function supportCoroutines(): bool
    {
        return true;
    }
}
