<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Src\Core\Additional\EmailSender;
use Src\Models\Views\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class DriverInspectionDaysLeftMail
 * @package Src\Mail
 */
class DriverInspectionDaysLeftMail extends Mailable
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
    public function __construct($days)
    {
        $this->body = new EmailSender(EmailTemplate::INSPECTION_DAYS_LEFT_DRIVER, ['days' => $days]);
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
