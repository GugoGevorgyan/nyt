<?php

declare(strict_types=1);

namespace Src\Console\RoleCommands;

use Illuminate\Console\Command;
use Src\Core\Additional\RoleRegister;

/**
 * Class CacheReset
 * @package Src\Console\RoleCommands
 */
class CacheReset extends Command
{
    /**
     * @var string
     */
    protected $signature = 'permission:cache-reset';
    /**
     * @var string
     */
    protected $description = 'Reset the permission cache';

    /**
     *
     */
    public function handle(): void
    {
        app(RoleRegister::class)->forgetCachedPermissions();

        $this->info('Permission cache flushed.');
    }
}
