<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Src\Events\CreatedSystemWorker;
use Src\Broadcasting\Mail\SendSystemWorkerCreateMail;

/**
 * Class SendWorkerCreatedData
 * @package Src\Listeners
 */
class SendWorkerCreatedData implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreatedSystemWorker  $event
     * @return void
     */
    public function handle(CreatedSystemWorker $event): void
    {
        $params = ['login' => $event->nickname, 'password' => $event->password];

        Mail::to($event->email)->queue(new SendSystemWorkerCreateMail($params, $event->name));
    }
}
