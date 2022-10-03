<?php

declare(strict_types=1);


namespace Src\Listeners;

use Exception;
use RuntimeException;
use Src\Services\MenuService\MenuServiceContract;

/**
 * Class UserLoginListener
 * @package Src\Listeners
 */
class FranchiseAdminMenuGenerator
{
    /**
     * Create the event listener.
     *
     * @param MenuServiceContract $menuService
     */
    public function __construct(protected MenuServiceContract $menuService)
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return bool
     * @throws Exception
     */
    public function handle(object $event): bool
    {
        if (!$this->menuService->getMenu($event->user->system_worker_id)) {
            throw new RuntimeException('Menu Generate Error', 500);
        }

        return true;
    }
}
