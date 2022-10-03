<?php

declare(strict_types=1);

namespace Src\Models\Driver;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Src\Models\ApiKey;

/**
 * Src\Models\Driver\ExternalBoard
 *
 * @property int $external_board_id
 * @property int $key_id
 * @property string $name
 * @property int $passed_count
 * @property int $completed_count
 * @property mixed $oauth_payload
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read ApiKey $key
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereCompletedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereExternalBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereOauthPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard wherePassedCount($value)
 * @mixin \Eloquent
 */
class ExternalBoard extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'external_boards';
    /**
     * @var string
     */
    protected $primaryKey = 'external_board_id';
    /**
     * @var string[]
     */
    protected $fillable = ['key_id', 'name'];
    /**
     * @var string
     */
    protected string $map = 'externalBoard';

    /**
     * @return BelongsTo
     */
    public function key(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class, 'key_id', 'api_key_id');
    }
}
