<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\CallCenter;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Models\ComplaintsResource;
use Src\Http\Resources\Models\CompletedOrderCrossResource;
use Src\Http\Resources\Models\CompletedOrderResource;
use Src\Http\Resources\Models\CorporateOrderResource;
use Src\Http\Resources\Models\OrderFeedbackResource;
use Src\Http\Resources\Models\OrderResource;
use Src\Http\Resources\Models\OrderStageResource;
use Src\Http\Resources\Models\RoadResource;
use Src\Http\Resources\Models\TariffResource;
use Src\Http\Resources\OrderAttachResource;
use Src\Http\Resources\Worker\OrderCommentsResource;
use Src\Http\Resources\Worker\OrderShippedDriverResources;

/**
 * Class OrderHistoryResource
 * @package Src\Http\Resources\Worker\CallCenter
 */
class OrderHistoryResource extends BaseResource
{
    /**
     * @var string
     */
    public static $wrap = '_payload';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'tariff' => $this['tariff'] ? new TariffResource($this['tariff']) : [],
            'order' => $this->resource->getAttributes() ? new OrderResource($this->resource) : [],
            'corporate' => $this['corporate'] ? new CorporateOrderResource($this['corporate']) : [],
            'completed' => $this['completed'] ? new CompletedOrderResource($this['completed']) : [],
            'crossing' => $this['crossing'] ? new CompletedOrderCrossResource($this['crossing']) : [],
            'attach' => $this['attach'] ? OrderAttachResource::collection($this['attach']) : [],
            'stages' => $this['stage'] ? new OrderStageResource($this['stage']) : [],
            'complaints' => $this['complaints'] ? ComplaintsResource::collection($this['complaints']) : [],
            'comments' => $this['worker_comments'] ? OrderCommentsResource::collection($this['worker_comments']) : [],
            'shipments' => $this['ordering_shipments'] ? OrderShippedDriverResources::collection($this['ordering_shipments']) : [],
            'shipped' => $this['selected_shipped'] ? new OrderShippedDriverResources($this['selected_shipped']) : [],
            'feedbacks' => $this['feedbacks'] ? OrderFeedbackResource::collection($this['feedbacks']) : [],
            'process_road' => new RoadResource($this['in_process_road']),
            'way_road' => new RoadResource($this['on_way_road']),
        ];
    }
}
