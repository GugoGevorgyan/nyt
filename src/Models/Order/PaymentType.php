<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Order\PaymentType
 *
 * @property int $payment_type_id
 * @property int $type
 * @property string $name
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Order\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereType($value)
 * @method static \Illuminate\Database\Query\Builder|PaymentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentType withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentType extends ServiceModel
{
    use SoftDeletes;

    public const CASH = 1;
    public const COMPANY = 2;
    public const CREDIT = 3;

    /**
     * @var string
     */
    protected $table = 'payment_types';

    /**
     * @var string
     */
    protected $primaryKey = 'payment_type_id';

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'name', 'text'];

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'payment_type_id', 'payment_type_id');
    }
}
