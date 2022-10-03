<?php

declare(strict_types=1);

namespace Src\Models\Tariff;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Tariff\TariffRentAlt
 *
 * @property int $tariff_rent_alt_id
 * @property int $rent_id
 * @property int $alt_id
 * @property string|null $alt_type
 * @property int|null $type
 * @property string $created_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|TariffRentAlt newModelQuery()
 * @method static Builder|TariffRentAlt newQuery()
 * @method static Builder|TariffRentAlt query()
 * @method static Builder|TariffRentAlt whereAltId($value)
 * @method static Builder|TariffRentAlt whereAltType($value)
 * @method static Builder|TariffRentAlt whereCreatedAt($value)
 * @method static Builder|TariffRentAlt whereRentId($value)
 * @method static Builder|TariffRentAlt whereTariffRentAltId($value)
 * @method static Builder|TariffRentAlt whereType($value)
 * @mixin Eloquent
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $altable
 * @property-read \Src\Models\Tariff\TariffRent $rent
 */
class TariffRentAlt extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'tariff_rent_alt';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_rent_alt_id';
    /**
     * @var array
     */
    protected $fillable = [
        'rent_id',
        'alt_id',
        'alt_type',
        'in_area',
    ];
    /**
     * @var string
     */
    protected string $map = 'tariffAlt';

    /**
     * @return MorphTo
     */
    public function altable(): MorphTo
    {
        return $this->morphTo('rent_alt', 'alt_type', 'alt_id', 'in_area');
    }

    /**
     * @return BelongsTo
     */
    public function rent(): BelongsTo
    {
        return $this->belongsTo(TariffRent::class, 'rent_id', 'tariff_rent_id');
    }
}
