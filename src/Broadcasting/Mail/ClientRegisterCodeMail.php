<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Additional\EmailSender;
use Src\Models\Views\EmailTemplate;

/**
 * Class ClientRegisterCodeMail
 * @package Src\Mail
 */
class ClientRegisterCodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var
     */
    protected $key;
    /**
     * @var EmailSender|null
     */
    protected ?EmailSender $body = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->body = new EmailSender(EmailTemplate::CLIENT_REGISTER_CODE_EMAIL, ['key' => $key]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('vendor.mail.client-code-agree', ['body' => $this->body]);
    }
}
