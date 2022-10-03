<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Driver\DriverResource;
use Src\Http\Resources\Worker\MechanicReportResource;

/**
 * Class WaybillResource
 * @package Src\Http\Resources
 */
class WaybillResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'waybill_id' => $this->waybill_id,
            'car' => new CarResource($this->car),
            'driver' => new DriverResource($this->driver),
            'report' => new MechanicReportResource($this->report),
            'terminal_id' => $this->terminal_id,
            'worker_id' => $this->system_worker_id,
            'waybill_number_id' => $this->waybill_number_id,
            'organisation' => $this->organisation,
            'certificate' => $this->certificate,
            'line departure_fixed' => $this->departure_fixed,
            'line departure_actually' => $this->departure_actually,
            'return_park_fixed' => $this->return_park_fixed,
            'return_park_actually' => $this->return_park_actually,
            'personnel_number' => $this->personnel_number,
            'date_of_issue' => $this->date_of_issue,
            'date_of_issue_number' => $this->date_of_issue_number,
            'class' => $this->class,
            'license_card' => $this->license_card,
            'registration number' => $this->registration,
            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
