<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Src\Core\Additional\EmailSender;
use Src\Models\Views\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendSystemWorkerCreateMail
 * @package Src\Mail
 */
class SendSystemWorkerCreateMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    /**
     * @var
     */
    protected EmailSender $emailBody;

    /**
     * @var string
     */
    protected string $username;

    /**
     * Create a new message instance.
     *
     * @param $params
     * @param  string  $name
     */
    public function __construct($params, $name = '')
    {
        $this->emailBody = new EmailSender(EmailTemplate::SYSTEM_WORKER_CREATED_ACCOUNT_DATA, $params);
        $this->username = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vendor.mail.send-worker-created-data')
            ->with(
                [
                    'body' => $this->emailBody,
                    'redirect_link' => config('nyt.app_url').config('nyt.app_franchise_admin_url'),
                    'username' => $this->username,
                ]
            );
    }
}
