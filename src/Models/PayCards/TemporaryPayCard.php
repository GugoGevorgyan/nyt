<?php

declare(strict_types=1);

namespace Src\Models\PayCards;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ServiceEntity\Models\ServiceModel;
use Src\Models\PayCards\PayCard;

/**
 * Class TemporaryPayCard
 *
 * @package Src\Models\PayCards
 * @property int $temporary_pay_card_id
 * @property int $owner_id
 * @property string $owner_type
 * @property string $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereTemporaryPayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TemporaryPayCard extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'temporary_pay_cards';

    /**
     * @var string
     */
    protected $primaryKey = 'temporary_pay_card_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'transaction_id'
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasOne
     */
    public function pay_card(): HasOne
    {
        return $this->hasOne(PayCard::class, $this->primaryKey, $this->primaryKey);
    }
}
