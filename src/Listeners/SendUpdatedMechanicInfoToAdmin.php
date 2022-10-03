<?php

declare(strict_types=1);

namespace Src\Listeners;

use Src\Broadcasting\Mail\SendMechanicUpdateInfoMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendUpdatedMechanicInfoToAdmin
 * @package Src\Listeners
 */
class SendUpdatedMechanicInfoToAdmin implements ShouldQueue
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
     * @param $event
     */
    public function handle($event): void
    {
        Mail::to($event->email)->send(new SendMechanicUpdateInfoMail(['name' => $event->name, 'admin' => $event->admin]));
    }
}
