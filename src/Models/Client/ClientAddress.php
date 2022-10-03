<?php

declare(strict_types=1);

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\ClientAddress
 *
 * @property int $client_address_id
 * @property string|null $name
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @method static Builder|ClientAddress newModelQuery()
 * @method static Builder|ClientAddress newQuery()
 * @method static Builder|ClientAddress query()
 * @method static Builder|ClientAddress whereAddress($value)
 * @method static Builder|ClientAddress whereClientAddressId($value)
 * @method static Builder|ClientAddress whereCreatedAt($value)
 * @method static Builder|ClientAddress whereName($value)
 * @method static Builder|ClientAddress whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $namespace
 * @property string|null $value
 * @property string|null $displayName
 * @property string|null $type
 * @property string|null $porch
 * @property string|null $driverHint
 * @property array|null $coordinates
 * @property int|null $favorite
 * @property string $icon
 * @property-read int|null $clients_count
 * @method static Builder|ClientAddress whereCoordinates($value)
 * @method static Builder|ClientAddress whereDisplayName($value)
 * @method static Builder|ClientAddress whereDriverHint($value)
 * @method static Builder|ClientAddress whereFavorite($value)
 * @method static Builder|ClientAddress whereIcon($value)
 * @method static Builder|ClientAddress whereNamespace($value)
 * @method static Builder|ClientAddress wherePorch($value)
 * @method static Builder|ClientAddress whereType($value)
 * @method static Builder|ClientAddress whereValue($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $client_id
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientAddress whereClientId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $short_address
 * @property string|null $driver_hint
 * @method static Builder|ClientAddress whereShortAddress($value)
 * @property string $lat
 * @property string $lut
 * @method static Builder|ClientAddress whereLat($value)
 * @method static Builder|ClientAddress whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class ClientAddress extends ServiceModel
{

    /**
     * @var string
     */
    protected $table = 'client_addresses';
    /**
     * @var string
     */
    protected $primaryKey = 'client_address_id';
    /**
     * @var array
     */
    protected $casts = ['coordinates' => 'array'];
    /**
     * @var array
     */
    protected $fillable = ['name', 'client_id', 'short_address', 'address', 'coordinates', 'driver_hint', 'favorite', 'lat', 'lut'];

    /**
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(
            Client::class,
            'address_client',
            'client_address_id',
            'client_id'
        )->withPivot('company_id')->using(__CLASS__);
    }

    public function favorite()
    {

    }
}
