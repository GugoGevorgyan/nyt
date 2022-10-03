<?php

declare(strict_types=1);

namespace Src\Listeners;

use Src\Events\TrafficSafetyCheckEvent;
use Src\Broadcasting\Mail\ParkManagerInspectionDaysLeftMail;
use Src\Broadcasting\Mail\ParkManagerInspectionExpirationMail;
use Src\Broadcasting\Mail\ParkManagerInsuranceDaysLeftMail;
use Src\Broadcasting\Mail\ParkManagerInsuranceExpirationMail;
use Mail;

/**
 * Class ExpirationMailToParkManager
 * @package Src\Listeners
 */
class ExpirationMailToParkManager
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param TrafficSafetyCheckEvent $event
     * @return void
     */
    public function handle(TrafficSafetyCheckEvent $event)
    {
        $leftWeekInspection = $event->cars['leftWeekInspection'];
        $leftDayInspection = $event->cars['leftDayInspection'];
        $expiredInspection = $event->cars['expiredInspection'];

        $leftWeekInsurance = $event->cars['leftWeekInsurance'];
        $leftDayInsurance = $event->cars['leftDayInsurance'];
        $expiredInsurance = $event->cars['expiredInsurance'];

        //       Inspection

        $leftDayInspection->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInspectionDaysLeftMail($car, 1));
            }
        });

        $leftWeekInspection->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInspectionDaysLeftMail($car, 7));
            }
        });

        $expiredInspection->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInspectionExpirationMail($car));
            }
        });

        //       Insurance

        $leftDayInsurance->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInsuranceDaysLeftMail($car, 1));
            }
        });

        $leftWeekInsurance->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInsuranceDaysLeftMail($car, 7));
            }
        });

        $expiredInsurance->map(function ($car) {
            if ($car->park) {
                Mail::to($car->park->parkManager->email)->queue(new ParkManagerInsuranceExpirationMail($car));
            }
        });
    }
}
