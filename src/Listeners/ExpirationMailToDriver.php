<?php

declare(strict_types=1);

namespace Src\Listeners;

use Src\Events\TrafficSafetyCheckEvent;
use Src\Broadcasting\Mail\DriverInspectionDaysLeftMail;
use Src\Broadcasting\Mail\DriverInspectionExpirationMail;
use Src\Broadcasting\Mail\DriverInsuranceDaysLeftMail;
use Src\Broadcasting\Mail\DriverInsuranceExpirationMail;
use Mail;

/**
 * Class ExpirationMailToDriver
 * @package Src\Listeners
 */
class ExpirationMailToDriver
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
     * @param  TrafficSafetyCheckEvent  $event
     * @return void
     */
    public function handle(TrafficSafetyCheckEvent $event): void
    {
        $leftWeekInspection = $event->cars['leftWeekInspection'];
        $leftDayInspection = $event->cars['leftDayInspection'];
        $expiredInspection = $event->cars['expiredInspection'];

        $leftWeekInsurance = $event->cars['leftWeekInsurance'];
        $leftDayInsurance = $event->cars['leftDayInsurance'];
        $expiredInsurance = $event->cars['expiredInsurance'];

//        Inspection

        $leftDayInspection->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInspectionDaysLeftMail(1));
            });
        });

        $leftWeekInspection->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInspectionDaysLeftMail(7));
            });
        });

        $expiredInspection->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInspectionExpirationMail());
            });
        });
//        Insurance

        $leftWeekInsurance->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInsuranceDaysLeftMail(7));
            });
        });

        $leftDayInsurance->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInsuranceDaysLeftMail(1));
            });
        });

        $expiredInsurance->map(function ($car) {
            $car->drivers->map(function ($driver) {
                Mail::to($driver->email)->queue(new DriverInsuranceExpirationMail());
            });
        });

    }
}
