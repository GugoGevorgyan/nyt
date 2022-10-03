<?php

declare(strict_types=1);

namespace Src\Http\View\Composers;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\View\View;
use Src\Models\SystemUsers\SuperAdmin;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class AdminSuperAuthComposer
 * @package Src\Http\View\Composers
 */
class SystemWorkerAuthComposer
{
    protected $auth;

    public function __construct()
    {
        /** @var SystemWorker $user */
        $user = auth('system_workers_web')->user();

        if ($user) {
            $user->get(['system_worker_id', 'name', 'email']);
        }

        $this->auth = json_encode(
            [
                'check' => auth('system_workers_web')->check(),
                'user' => $user
            ],
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $view->with('auth', $this->auth);
    }
}
