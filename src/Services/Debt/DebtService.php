<?php

namespace Src\Services\Debt;

use ServiceEntity\BaseService;
use Src\Repositories\DriverDebt\DebtContract;

class DebtService extends BaseService implements DebtServiceContract
{
    public function __construct(protected DebtContract $debtContract)
    {
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function getPenalties($payload)
    {
        $per_page = $payload['per_page'] && is_numeric($payload['per_page']) ? $payload['per_page'] : '25';
        $page = is_numeric($payload['page']) ?: 1;
        if (isset($payload['search'])) {
            $search = $payload['search'];
        } else {
            $search = '';
        }

        if (isset($payload['date_start']) && isset($payload['date_end'])) {
            $betweenDate = [$payload['date_start'], $payload['date_end']];
        } else {
            $betweenDate = null;
        }

        $penalties = $this->debtContract
            ->with([
            'penalty' => fn($q) => $q->when($betweenDate, fn($buildQuery) => $buildQuery->whereBetween('offense_date', $betweenDate))->select(['*']),
            'current_debt' => fn($query) => [
                $query->whereHas('driver_info', fn($q) => $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%'))
                    ->with([
                        'car' => fn($q) => $q->select(['car_id', 'mark', 'model', 'sts_number', 'pts_number', 'park_id']),
                        'park' => fn($q) => $q->select(['name', 'address']),
                        'driver_info' => fn($q) => $q->select(['driver_info_id', 'name', 'surname'])
                    ])->select(['driver_id', 'phone', 'selected_class', 'car_id', 'driver_info_id'])
            ],
        ])->paginate($per_page, ['*'], 'page', $page);

        if (array_key_exists('search',$payload) || array_key_exists('search',$payload)) {
            $penalties_data = [
                'data' => [],
                'current_page' => $penalties->toArray()['current_page'],
                'first_page_url' => $penalties->toArray()['first_page_url'],
                'from' => $penalties->toArray()['from'],
                'last_page' => $penalties->toArray()['last_page'],
                'last_page_url' => $penalties->toArray()['last_page_url'],
                'links' => $penalties->toArray()['links'],
                'next_page_url' => $penalties->toArray()['next_page_url'],
                'prev_page_url' => $penalties->toArray()['prev_page_url'],
                'path' => $penalties->toArray()['path'],
                'per_page' => $penalties->toArray()['per_page'],
                'to' => $penalties->toArray()['to'],
                'total' => $penalties->toArray()['total'],
            ];

            foreach ($penalties->toArray()['data'] as $penalty) {
                if (!is_null($penalty['current_debt']) && !is_null($penalty['penalty'])) {
                    $penalties_data['data'][] = $penalty;
                    $penalties_data['debt_id'] = $penalties['debt_id'];
                    $penalties_data['cost'] = $penalties['cost'];
                    $penalties_data['firm_paid'] = $penalties['firm_paid'];
                }
            }

            return $penalties_data;
        }

        return $penalties;
    }

    /**
     * @param $id
     * @param $value
     * @return mixed
     */
    public function toPay($id, $value): mixed
    {
        return $this->debtContract
            ->where('debt_id', '=', $id)
            ->updateSet(['firm_paid' => $value]);
    }
}
