<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Additional\EmailSender;
use Src\Models\Views\EmailTemplate;

/**
 * Class DriverInspectionExpirationMail
 * @package Src\Mail
 */
class DriverInspectionExpirationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    /**
     * @var EmailSender
     */
    protected $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->body = new EmailSender(EmailTemplate::INSPECTION_EXPIRED_DRIVER, []);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vendor.mail.client-code-agree', ['body' => $this->body]);
    }
}
