<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\Corporate\AdminCorporate;

/**
 * Class AdminCorporateWebChannel
 * @package Src\Broadcasting\Channels
 */
class AdminCorporateWebChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  AdminCorporate  $adminCorporate
     * @param $admin_id
     * @param $company_id
     * @param $franchise_id
     * @return bool|null
     */
    public function join(AdminCorporate $adminCorporate, $admin_id, $company_id, $franchise_id): ?bool
    {
        return !($adminCorporate->admin_corporate_id !== (int)$admin_id && $adminCorporate->company_id !== (int)$company_id && $adminCorporate->franchise_id !== (int)$franchise_id);
    }
}
