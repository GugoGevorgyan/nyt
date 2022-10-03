<?php

declare(strict_types=1);

namespace Src\Broadcasting\Mail;

use Src\Core\Additional\EmailSender;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Src\Models\Views\EmailTemplate;

/**
 * Class ParkManagerInsuranceDaysLeftMail
 * @package Src\Mail
 */
class ParkManagerInsuranceDaysLeftMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    /**
     * @var EmailSender
     */
    protected EmailSender $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($car, $days)
    {
        $props = [
            'days' => $days,
            'mark' => $car->mark,
            'model' => $car->model,
            'vin_code' => $car->vin_code,
            'drivers' => $car->drivers->implode('name', ', '),
        ];
        $this->body = new EmailSender(EmailTemplate::INSPECTION_DAYS_LEFT_PARK_ADMIN, $props);
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
