<?php

declare(strict_types=1);

namespace ServiceEntity;

use Eloquent;
use Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use ServiceEntity\Contract\BaseContract;
use Spatie\Async\Pool;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRedis;
use Src\Jobs\OrderProcessing\OrderDistributor;
use Src\Repositories\Order\OrderContract;
use Src\Support\Facades\Image;

use function redis;

/**
 * Class service
 * @return Eloquent;
 * @package ServiceEntityStory
 */
class BaseService implements BaseContract
{
    /**
     * @inheritDoc
     */
    public function hasPerson($contract, $attribute, $value, $password)
    {
        $contract = $this->{$contract.'Contract'};

        $person = $contract->findBy($attribute, $value, ['password', $contract->getKeyName()]);

        if (!Hash::check($password, $person->password)) {
            return false;
        }

        return $person->{$contract->getKeyName()};
    }

    /**
     * @param $model
     * @param $id
     * @return mixed
     */
    public function getRoleNameById($model, $id)
    {
        return (new $model())->find($id)->getRoleNames();
    }

    /**
     * @return Pool
     */
    public function poolAsync(): Pool
    {
        return Pool::create();
    }

    /**
     * @return Pool
     */
    public function pool(): Pool
    {
        return Pool::create();
    }

    /**
     * @param  object  $relations
     * @return array
     */
    public function getRelationAttributes(object $relations): ?array
    {
        $data = [];

        foreach ($relations as $relation) {
            $data[] = $relation->getAttributes();
        }

        return $data;
    }

    /**
     * @param  Worksheet  $sheet
     * @param  array  $coordinates
     * @param  string  $value
     */
    public function setSheetCellsValue(Worksheet $sheet, array $coordinates, $value): void
    {
        foreach ($coordinates as $coordinate) {
            $sheet->setCellValue($coordinate, $value);
        }
    }

    /**
     * @param $order_id
     */
    public function removeRedisKeys($order_id, $client_id = null): void
    {
        $redis = $this->redis();

        if ($redis->hExists(ConstRedis::order_create_data($order_id), 'order_data')) {
            $redis->hDel(ConstRedis::order_create_data($order_id), ...['order_data', 'price_data']);
        }

        if ($redis->hExists(ConstRedis::order_calc_data($order_id), 'driver')) {
            $redis->hDel(ConstRedis::order_calc_data($order_id), ...[
                'stages',
                'driver',
                'road',
                'real_road',
                'initial_tariff',
                'second_tariff',
                'destination_tariff',
                'min_distance',
                'min_duration'
            ]);
        }

        if ($redis->hExists(ConstRedis::order_process_road($order_id), 'road')) {
            $redis->hDel(ConstRedis::order_process_road($order_id), ...['road']);
        }

        if ($client_id && $redis->hExists(ConstRedis::order_calc_response($client_id), 'tariff')) {
            $redis->hDel(ConstRedis::order_calc_response($client_id), ...['tariff', 'prices']);
        }

        if ($redis->hExists(ConstRedis::order_pause_time($order_id), 'time')) {
            $redis->hDel(ConstRedis::order_pause_time($order_id), ...['time', 'passed']);
        }
    }

    /**
     * @inheritDoc
     */
    public function redis(string $connection = 'app'): Connection
    {
        return redis();
    }

    /**
     * @param $order_id
     * @return bool
     */
    public function autoDispatch($order_id): bool
    {
        $order = app(OrderContract::class)->find($order_id);
        $_order = redis()->hmget(ConstRedis::order_create_data($order_id), ['price_data', 'order_data']);

        if (!$_order[0] || !$_order[1]) {
            return false;
        }

        $_price_data = igus($_order[0]);
        $_order_data = igus($_order[1]);

        $additional_params = [
            'preorder' => $_order_data['preorder'],
            'meet' => $_order_data['meet'],
            'passenger' => $_order_data['passenger'],
            'corporate' => $_order_data['corporate']
        ];

        OrderDistributor::dispatch($order, $additional_params, $_price_data['coin'])->onQueue(ConstQueue::LONG()->getValue());

        return true;
    }

    /**
     * @param $instance
     * @param  mixed  ...$_i
     * @return \Illuminate\Support\Collection
     */
    protected function parseResult(&$instance, ...$_i): \Illuminate\Support\Collection
    {
        $new_datum = !empty($_i) ? array_combine($_i[0], $_i[1]) : [];

        if (!empty($new_datum)) {
            if ($instance instanceof Collection) {
                foreach ($new_datum as $key => $value) {
                    $instance = $instance->put($key, $value);
                }
            } elseif ($instance instanceof Model) {
                foreach ($new_datum as $key => $value) {
                    $instance->{$key} = $value;
                }

                $instance = collect($instance->getAttributes())->merge($instance->relationsToArray());
            } else {
                $instance = collect($instance)->merge(collect($new_datum));
            }
        } else {
            $instance = !$instance instanceof Collection ? collect($instance) : $instance;
        }

        return $instance;
    }

    /**
     * @param $file
     * @param $path
     * @param  array|null  $resize
     * @return string|null
     */
    protected function fileUpload($file, $path, array $resize = null): ?string
    {
        if (!$file) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();

        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $data_name = hash('sha256', $name.microtime());
        $image = "$data_name.$extension";

        $file->move($path, $image);

        if ($resize) {
            $resize = ['width' => $resize[0], 'height' => $resize[1]];
            $this->resizeImg($path.$image, $resize);
        }

        return $image;
    }

    /**
     * @param  string  $path_image
     * @param  array  $size
     */
    protected function resizeImg(string $path_image, array $size): void
    {
        if (!\extension_loaded('imagick')) {
            $new_image = Str::afterLast($path_image, DS);
            $new_path = Str::beforeLast($path_image, DS);

            if (Storage::put($new_path, $new_image)) {
                $this->deleteOldFile($path_image);
            }

            return;
        }

        $resize_image = Image::resizeImage($path_image, $size['width'], $size['height']);

        $new_image = Str::afterLast($resize_image, DS);
        $new_path = Str::beforeLast($new_image, DS);

        if (Storage::put($new_path, $new_image)) {
            $this->deleteOldFile($path_image);
        }
    }

    /**
     * @param $file
     */
    protected function deleteOldFile($file): void
    {
        if (Storage::disk('public')->exists(str_replace_first('/storage', '', $file))) {
            Storage::disk('public')->delete(str_replace_first('/storage', '', $file));
        }
    }
}
