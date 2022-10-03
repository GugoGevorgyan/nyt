<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Src\Core\Additional\EmailSender;
use Src\Models\Views\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendMechanicUpdateInfoMail
 * @package Src\Mail
 */
class SendMechanicUpdateInfoMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    /**
     * @var
     */
    protected EmailSender $emailBody;

    /**
     * @var mixed
     */
    protected $username;

    /**
     * Create a new message instance.
     *
     * @param $params
     * @param  string  $name
     */
    public function __construct($params)
    {
        $this->emailBody = new EmailSender(EmailTemplate::MECHANIC_INFORMATION_UPDATE, $params);
        $this->username = $params['admin'];
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
                    'username' => $this->username,
                ]
            );
    }
}
