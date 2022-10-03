<?php

declare(strict_types=1);

namespace Src\Http\View\Composers;

use Illuminate\View\View;
use Src\Models\SuperAdmin;

/**
 * Class AdminSuperAuthComposer
 * @package Src\Http\View\Composers
 */
class AdminSuperAuthComposer
{
    protected $auth;

    public function __construct()
    {
        /** @var SuperAdmin $user */
        $user = auth('admin_super_web')->user();

//        if ($user) {
//            $user->load([
//                'profile_image' => static function (MorphOne $image_query) {
//                    $image_query->select();
//                }
//            ])->get(['super_admin_id', 'name', 'email']);
//        }

        $this->auth = json_encode(
            [
                'check' => auth('admin_super_web')->check(),
                'user' => $user
            ]
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
