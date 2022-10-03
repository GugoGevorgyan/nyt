<?php

declare(strict_types=1);

namespace Src\Models\PayCards;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Class PayCard
 *
 * @package Src\Models\PayCards
 * @property int $pay_card_id
 * @property int $temporary_pay_card_id
 * @property int $owner_id
 * @property string $owner_type
 * @property string|null $card_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard wherePayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereTemporaryPayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PayCard extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'pay_cards';

    /**
     * @var string
     */
    protected $primaryKey = 'pay_card_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'card_number'
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
