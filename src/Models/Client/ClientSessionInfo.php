<?php

declare(strict_types=1);

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class ClientSessionInfo
 *
 * @package Src\Models\ClientMessage
 * @property int $client_session_info_id
 * @property int $clientable_id
 * @property string $clientable_type
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string|null $ip_address
 * @property string|null $device
 * @property string|null $platform
 * @property int $mobile
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $client
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientSessionInfo newModelQuery()
 * @method static Builder|ClientSessionInfo newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo onlyTrashed()
 * @method static Builder|ClientSessionInfo query()
 * @method static Builder|ClientSessionInfo whereCityId($value)
 * @method static Builder|ClientSessionInfo whereClientSessionInfoId($value)
 * @method static Builder|ClientSessionInfo whereClientableId($value)
 * @method static Builder|ClientSessionInfo whereClientableType($value)
 * @method static Builder|ClientSessionInfo whereCountryId($value)
 * @method static Builder|ClientSessionInfo whereCreatedAt($value)
 * @method static Builder|ClientSessionInfo whereDeletedAt($value)
 * @method static Builder|ClientSessionInfo whereDevice($value)
 * @method static Builder|ClientSessionInfo whereIpAddress($value)
 * @method static Builder|ClientSessionInfo whereMobile($value)
 * @method static Builder|ClientSessionInfo wherePlatform($value)
 * @method static Builder|ClientSessionInfo whereRegionId($value)
 * @method static Builder|ClientSessionInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class ClientSessionInfo extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'clients_session_info';
    /**
     * @var string
     */
    protected $primaryKey = 'client_session_info_id';
    /**
     * @var array
     */
    protected $fillable = ['clientable_id', 'clientable_type', 'ip_address', 'country_id', 'region_id', 'city_id', 'device', 'platform', 'mobile'];

    /**
     * Morphed ClientMessage and BeforeAuthClient MODELS
     *
     * @return MorphTo
     */
    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
