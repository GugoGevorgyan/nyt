<?php

declare(strict_types=1);

namespace Src\Models\Oauth;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class FcmClient
 *
 * @package Src\Models\Oauth
 * @property int $fcm_client_id
 * @property int $client_id
 * @property string $client_type
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $client
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FcmClient newModelQuery()
 * @method static Builder|FcmClient newQuery()
 * @method static Builder|FcmClient query()
 * @method static Builder|FcmClient whereClientId($value)
 * @method static Builder|FcmClient whereClientType($value)
 * @method static Builder|FcmClient whereCreatedAt($value)
 * @method static Builder|FcmClient whereFcmClientId($value)
 * @method static Builder|FcmClient whereKey($value)
 * @method static Builder|FcmClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class FcmClient extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'fcm_clients';
    /**
     * @var string
     */
    protected $primaryKey = 'fcm_client_id';
    /**
     * @var string[]
     */
    protected $fillable = ['key', 'client_id', 'client_type'];

    /**
     * @return MorphTo
     */
    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
