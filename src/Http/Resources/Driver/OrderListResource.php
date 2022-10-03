<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderListResource
 * @property Collection stage
 * @property int completed_order_id
 * @property string address_from
 * @property string address_to
 * @property int distance
 * @property int duration
 * @property float cost
 * @property string tarted
 * @property string ended
 * @property array trajectory
 * @package Src\Http\Resources\Driver
 */
class OrderListResource extends JsonResource
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
            'completed_order_id' => $this->completed_order_id,
            'from' => $this->order->address_from,
            'to' => $this->destination_address ?? $this->order->address_to,
            'distance' => $this->distance,
            'duration' => $this->duration,
            'price' => $this->cost,
            'started' => Carbon::parse($this->stage->started)->format('H:i d/m/Y'),
            'ended' => Carbon::parse($this->stage->ended)->format('H:i d/m/Y') ?? Carbon::parse($this->created_at)->format('H:i d/m/Y'),
        ];
    }
}
