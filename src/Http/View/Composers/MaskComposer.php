<?php

namespace Src\Http\View\Composers;

use Illuminate\View\View;
use Src\Repositories\SystemWorker\SystemWorkerContract;

class MaskComposer
{
    public function __construct(protected SystemWorkerContract $systemWorkerContract)
    {
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
            $systemWorker = $this->systemWorkerContract->where('system_worker_id','=', get_user_id())
                ->with(['franchise' => fn($query) => $query->with(
                    'country', fn($q) => $q->select(['country_id','phone_mask'])
                )])->findFirst(['system_worker_id','franchise_id']);

            $view->with('mask',$systemWorker['franchise']['country']['phone_mask']);
    }

}
