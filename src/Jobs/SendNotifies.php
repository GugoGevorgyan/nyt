<?php

declare(strict_types=1);

namespace Src\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Spatie\Async\Pool;
use Src\Core\Traits\Complex;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Notiication\NotificationContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * @property DriverContract $driverContract,
 * @property ClientContract $clientContract,
 * @property NotificationContract $notificationContract,
 * @property SystemWorkerContract $systemWorkerContract
 */
class SendNotifies implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Complex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected array $notifiers,
        protected string $notifierType,
        protected $workerAnnunciatorId,
        protected string $title = '',
        protected string $text = '',
        protected string $image = '',
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->inject(DriverContract::class, ClientContract::class, NotificationContract::class, SystemWorkerContract::class);

        $this->notify();
    }

    protected function notify(): void
    {
        switch ($this->notifierType) {
            case $this->driverContract->getMap();
                $senders = $this->driverContract
                    ->whereIn('driver_id', $this->notifiers)
                    ->findAll(['driver_id', 'car_id', 'phone', 'current_franchise_id']);
                $notification = '\Src\Broadcasting\Notifications\DriverNotify';
                break;
            case $this->clientContract->getMap();
                $senders = $this->clientContract
                    ->whereIn('client_id', $this->notifiers)
                    ->findAll(['client_id', 'phone']);
                $notification = '\Src\Broadcasting\Notifications\ClientNotify';
                break;
            default:
        }

        if (!$senders) {
            return;
        }

        $last_number = $this->notificationContract->firstLatest('notification_id', ['group_number'])->group_number ?? '0000000000';
        ++$last_number;

        if (Str::startsWith($last_number, '0')) {
            preg_match('/[0]+/', $last_number, $matches);
            $group_number = $matches[0].($last_number);
            $group_number = \strlen($group_number) > 10 ? substr($group_number, \strlen($group_number) - 10) : $group_number;
        } else {
            $group_number = $last_number;
        }


        $pool = Pool::create()
            ->autoload(__DIR__.'/../../app/AsyncLoader/RuntimeAutoload.php')
            ->concurrency(4)
            ->timeout(10);

        $notification_data = [];
        foreach ($senders as $sender) {
            $pool->add(function () use ($group_number, $sender, &$notification_data) {
                $notification_data[] = [
                    'group_number' => $group_number,
                    'annunciator_id' => $sender->{$sender->getKeyName()},
                    'annunciator_type' => $this->notifierType,
                    'notifier_id' => $this->workerAnnunciatorId,
                    'notifier_type' => $this->systemWorkerContract->getMap(),
                    'title' => $this->title,
                    'body' => $this->text,
                    'image' => $this->image,
                ];
            })->then(function () use ($notification, $sender, $group_number) {
                Notification::sendNow($sender, (new $notification($group_number, ['title' => $this->title, 'body' => $this->text, 'image' => $this->image])));
            });
        }
        $pool->wait();

        $this->notificationContract->insert($notification_data);
    }
}
