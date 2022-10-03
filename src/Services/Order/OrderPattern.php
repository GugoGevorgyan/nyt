<?php

namespace Src\Services\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Src\Core\Enums\ConstRedis;
use Src\Models\Client\Client;
use Src\Models\Complaint\Complaint;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverContract;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Complaint\ComplaintContract;
use Src\Repositories\OrderFeedback\OrderFeedbackContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Tariff\TariffServiceContract;

/**
 *
 */
class OrderPattern
{
    /**
     * @param  TariffServiceContract  $tariffService
     * @param  PreorderContract  $preOrderContract
     * @param  ComplaintContract  $complaintContract
     * @param  OrderFeedbackContract  $feedbackContract
     * @param  ClientContract  $clientContract
     * @param  DriverContract  $driverContract
     */
    public function __construct(
        protected TariffServiceContract $tariffService,
        protected PreorderContract $preOrderContract,
        protected ComplaintContract $complaintContract,
        protected OrderFeedbackContract $feedbackContract,
        protected ClientContract $clientContract,
        protected DriverContract $driverContract
    ) {
    }

    /**
     * @return string[]
     */
    public function viewOrderRelations(): array
    {
        return [
            'status',
            'paymentType',
            'orderType',
            'carClass',
            'car_options',
            'passenger',
            'meet',
            'client',
            'driver.car',
            'driver.driver_info',
            'preorder',
            'common',
            'customer',
            'operator',
            'completed',
            'canceled',
            'operator',
            'corporate.company',
            'process',
            'current_shipped.status'
        ];
    }

    /**
     * @param  array  $fields
     * @return array
     */
    public function getPrice(array $fields): array
    {
        $is_rent = (bool)($fields['rent'] ?? false);
        $options = [
            'payment_type' => $fields['order']['payment_type_id'],
            'car_class' => $fields['order']['car_class_id'],
            'demands' => $fields['order']['car_option']['ids'],
            'payment_type_company' => $fields['corporate'] ? $fields['corporate']['company_id'] : null,
            'rent_time' => $fields['rent']['hours'] ?? null,
        ];

        $tariff = $this->tariffService->getTariff($fields['order']['from_coordinates'],
            $fields['order']['to_coordinates'], $options, $is_rent);

        return $this->tariffService->getPriceByTariff($tariff, $fields['order']['from_coordinates'],
            $fields['order']['to_coordinates'], $options);
    }

    /**
     * @param $cord
     * @param $options
     * @param  bool  $rent
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    public function existedPriceRequest($cord, $options, bool $rent = false): ?array
    {
        $_payload = redis()->hGet(ConstRedis::order_calc_request(get_user_id()), 'init_coin');
        $existed_price = $_payload ? igus($_payload) : '';

        if (!$existed_price) {
            return null;
        }

        if (($existed_price['route']['to'] && !$cord['to']) || (!$existed_price['route']['to'] && $cord['to'])) {
            return null;
        }

        if ($existed_price['route']['from'][0] !== $cord['from'][0] || $existed_price['route']['from'][1] !== $cord['from'][1]) {
            return null;
        }

        if ($existed_price['route']['to'] && $cord['to']) {
            if ($existed_price['route']['to'][0] !== $cord['to'][0] || $existed_price['route']['to'][1] !== $cord['to'][1]) {
                return null;
            }
        }

        if ($rent !== $existed_price['is_rent']) {
            return null;
        }

        if ($rent && $options['rent_time'] !== $existed_price['rent_time']) {
            return null;
        }

        if ((int)$existed_price['payment']['type'] !== (int)$options['payment_type'] || (int)$existed_price['payment']['company'] !== (int)$options['payment_type_company']) {
            return null;
        }

        if ($options['demands'] !== $existed_price['car']['options']) {
            return null;
        }

        if (15 < Carbon::parse($existed_price['time']['create_time'])->diffInMinutes(Carbon::parse($options['time']))) {
            return null;
        }

        $saved_data = redis()->hMGet(ConstRedis::order_calc_response(get_user_id()), ['prices', 'tariff']);

        if (!$saved_data[0] || !$saved_data[1]) {
            return null;
        }

        [$prices, $tariffs] = [igus($saved_data[0]), igus($saved_data[1])];

        if (!$prices = $this->reCalcOptions($existed_price, $prices, $options)) {
            return null;
        }

        return [$prices, $tariffs];
    }

    /**
     * @param  array  $existed_price
     * @param  array  $prices
     * @param  array  $options
     * @return array|null
     */
    public function reCalcOptions(array $existed_price, array $prices, array $options): ?array
    {
        // @todo non tested
        if (!\is_array($existed_price['car']['options']) || !\is_array($options['demands'])) {
            return null;
        }

        $slice_options = array_diff($existed_price['car']['options'], $options['demands']);
        $push_options = array_diff($options['demands'], $existed_price['car']['options']);

        if (!empty($slice_options) || !empty($push_options)) {
            $slice_price = [];
            $push_price = [];

            foreach ($prices as $key => $price) {
                // ========================== Slice Calc =====================
                if (!empty($slice_options)) {
                    $car_option_ids = array_values($slice_options);
                    if (1 === count($slice_options)) {
                        $car_option_index = $this->filterOptionsDiffData($price, $car_option_ids[0]);

                        if (!empty($car_option_index)) {
                            $slice_price[$car_option_index[0]] = $price['options'][$car_option_index[0]]['price'];
                        }
                    } else {
                        foreach ($car_option_ids as $car_option_id) {
                            $car_option_index = $this->filterOptionsDiffData($price, $car_option_id);

                            if (!empty($car_option_index)) {
                                foreach ($car_option_index as $index) {
                                    $slice_price[$index] = $price['options'][$index]['price'];
                                }
                            }
                        }
                    }
                }

                // ========================== Push Calc =====================
                if (!empty($push_options)) {
                    $car_option_ids = array_values($push_options);

                    if (1 === count($push_options)) {
                        $car_option_index = $this->filterOptionsDiffData($price, $car_option_ids[0]);
                        if (!empty($car_option_index)) {
                            $push_price[$car_option_index[0]] = $price['options'][$car_option_index[0]]['price'];
                        }
                    } else {
                        foreach ($car_option_ids as $car_option_id) {
                            $car_option_index = $this->filterOptionsDiffData($price, $car_option_id);

                            if (!empty($car_option_index)) {
                                foreach ($car_option_index as $index) {
                                    $push_price[$index] = $price['options'][$index]['price'];
                                }
                            }
                        }
                    }
                }

                // ========================== All Calc ====================
                foreach ($slice_price as $slice_key => $slice) {
                    if ($slice_price && $price['options'][$slice_key]['price']) {
                        $prices[$key]['coin'] -= $price['options'][$slice_key]['price'];
                    }
                }

                foreach ($push_price as $push_key => $push) {
                    if ($push_price && $price['options'][$push_key]['price']) {
                        $prices[$key]['coin'] += $price['options'][$push_key]['price'];
                    }
                }
            }
        }

        return $prices;
    }

    /**
     * @param $price
     * @param $car_option_id
     * @return array
     */
    public function filterOptionsDiffData($price, $car_option_id): array
    {
        return array_values(array_filter(array_map(static function ($key, $item) use ($car_option_id) {
            return $car_option_id === $item['car_option_id'] ? $key : null;
        }, array_keys($price['options']), $price['options']), static fn($item) => null !== $item));
    }

    /**
     * @param $order_id
     * @return string
     */
    public function orderDeliveryTime($order_id): string
    {
        $preorder = $this->preOrderContract->where('order_id', '=', $order_id)->findFirst();

        return $preorder && $preorder->time ? Carbon::parse($preorder->time)->format('Y-m-d H:i') : '0';
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     */
    public function callCenterWorkerComplaintCreate($request, $order): bool
    {
        $data = [
            'franchise_id' => FRANCHISE_ID,
            'writer_id' => user()->system_worker_id,
            'recipient_id' => $request->recipient_id,
            'status_id' => Complaint::COMPLAINT_STATUS_NEW,
            'subject' => $request->subject,
            'complaint' => $request->complaint
        ];

        $data['order_id'] = $order->order_id;

        return (bool)$this->complaintContract->create($data);
    }

    /**
     * @return string[]
     */
    public function operatorOrderRelations(): array
    {
        return [
            'status',
            'paymentType',
            'orderType',
            'carClass',
            'car_options',
            'passenger',
            'meet',
            'client',
            'driver.car',
            'driver.driver_info',
            'preorder',
            'common',
            'completed',
            'canceled',
            'customer',
            'operator',
            'company',
            'corporate.company',
            'process',
            'current_shipped.status'
        ];
    }

    /**
     * @return string[]
     */
    public function dispatcherOrderRelations(): array
    {
        return [
            'status' => fn($query) => $query->select(['order_status_id', 'status', 'name', 'text', 'color']),
            'paymentType' => fn($query) => $query->select(['type', 'name', 'text', 'payment_type_id']),
            'orderType' => fn($query) => $query->select(['order_type_id', 'name', 'text']),
            'carClass' => fn($query) => $query->select(['car_class_id', 'class_name', 'description', 'image', 'name']),
            'car_options' => fn($query) => $query->select(['car_option_id', 'name', 'option', 'value', 'price']),
            'passenger' => fn($query) => $query->select([
                'client_id',
                'name',
                'surname',
                'patronymic',
                'phone',
                'in_order',
                'only_passenger'
            ]),
            'meet' => fn($query) => $query->select(['order_meet_id', 'place_id', 'place_type', 'info', 'text']),
            'client' => fn($query) => $query->select([
                'client_id',
                'name',
                'surname',
                'patronymic',
                'phone',
                'in_order',
                'only_passenger'
            ]),
            'driver' => fn($query) => $query->with([
                'driver_info' => fn($query) => $query->select([
                    'driver_info_id',
                    'name',
                    'surname',
                    'patronymic',
                    'email',
                    'license_qr_code',
                    'license_code',
                    'license_scan',
                    'license_date',
                    'license_expiry',
                    'passport_serial',
                    'passport_scan',
                    'photo',
                    'experience',
                    'birthday',
                    'passport_number',
                    'passport_issued_by',
                    'passport_when_issued',
                    'citizen',
                    'address',
                    'deposit',
                    'id_kis_art'
                ]),
                'car' => fn($query) => $query->select([
                    'car_id',
                    'park_id',
                    'entity_id',
                    'class',
                    'current_driver_id',
                    'franchise_id',
                    'body_number',
                    'vehicle_licence_number',
                    'vehicle_licence_date',
                    'sts_number',
                    'pts_number',
                    'sts_file',
                    'pts_file',
                    'registration_date',
                    'option',
                    'mark',
                    'model',
                    'year',
                    'images',
                    'status_id',
                    'rating',
                    'inspection_date',
                    'inspection_expiration_date',
                    'inspection_scan',
                    'insurance_date',
                    'insurance_expiration_date',
                    'insurance_scan',
                    'speedometer',
                    'state_license_plate',
                    'garage_number',
                    'vin_code',
                    'color',
                ])
            ])
                ->select([
                    'drivers.driver_id',
                    'entity_id',
                    'driver_info_id',
                    'current_status_id',
                    'current_franchise_id',
                    'selected_class',
                    'selected_option',
                    'online',
                    'mean_assessment',
                    'rating',
                    'logged',
                    'is_ready',
                    'lat',
                    'lut',
                    'azimuth',
                    'nickname',
                    'device',
                    'password',
                    'phone',
                    'car_id',
                ]),
            'preorder' => fn($query) => $query->select([
                'order_id',
                'create_time',
                'distribution_start',
                'time',
                'active'
            ]),
            'common' => fn($query) => $query->select(['order_id', 'driver', 'accept', 'emergency']),
            'customer' => fn($query) => $query->select(['*']),
            'operator' => fn($query) => $query->select(['*']),
            'completed' => fn($query) => $query->select([
                'order_id',
                'driver_id',
                'car_id',
                'distance',
                'duration',
                'cost',
                'destination_address',
                'destination_lat',
                'destination_lut',
                'distance_price',
                'duration_price',
                'trajectory',
                'changed',
            ]),
            'canceled' => fn($query) => $query->select([
                'order_id',
                'driver_id',
                'car_id',
                'cancelable_id',
                'cancelable_type'
            ]),
            'operator',
            'corporate.company',
            'process' => fn($query) => $query->select([
                'order_shipped_id',
                'travel_time',
                'waiting_time',
                'pause_time',
                'distance_traveled',
                'speed',
                'price',
                'options_price',
                'calculate_price',
                'increment_price',
                'total_price',
                'pause_price',
                'sitting_price',
                'cancel_price',
                'waiting_price',
                'cord_updated',
                'price_passed',
            ]),
            'current_shipped.status',
            'shipped' => fn($query) => $query->with('status')->select('order_id','status_id'),
        ];
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     */
    public function callCenterClientFeedbackCreate($request, $order): bool
    {
        $data = [
            'text' => $request->text,
            'writable_id' => user()->system_worker_id,
            'writable_type' => (new SystemWorker())->getMap(),
            'readable_id' => $request->client_id,
            'readable_type' => (new Client())->getMap(),
            'assessment' => $request->assessment
        ];

        $data['order_id'] = $order->order_id;

        if (!$this->feedbackContract->create($data)) {
            return false;
        }

        $assessment = $this->clientContract
            ->with(['assessed' => fn(MorphMany $query) => $query->select('readable_id', 'readable_type', 'assessment')])
            ->find($request->client_id, ['client_id', 'mean_assessment']);

        if (!$assessment) {
            return false;
        }

        if (!$this->clientContract->update($request->client_id,
            ['mean_assessment' => $assessment->assessed->avg('assessment')])) {
            return false;
        }

        return true;
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     */
    public function callCenterDriverFeedbackCreate($request, $order): bool
    {
        $data = [
            'text' => $request->text,
            'writable_id' => user()->system_worker_id,
            'writable_type' => (new SystemWorker())->getMap(),
            'readable_id' => $request->driver_id,
            'readable_type' => (new Driver())->getMap(),
            'assessment' => $request->assessment
        ];

        $data['order_id'] = $order->order_id;

        if (!$this->feedbackContract->create($data)) {
            return false;
        }

        $assessment = $this->driverContract
            ->with(['assessed' => fn(MorphMany $query) => $query->select('readable_id', 'readable_type', 'assessment')])
            ->find($request->driver_id, ['driver_id', 'mean_assessment']);

        if (!$assessment) {
            return false;
        }

        if (!$this->driverContract->update($request->driver_id,
            ['mean_assessment' => $assessment->assessed->avg('assessment')])) {
            return false;
        }

        return true;
    }
}
