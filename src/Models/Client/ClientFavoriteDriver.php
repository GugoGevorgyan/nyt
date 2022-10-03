<?php

declare(strict_types=1);

namespace Src\Models\Client;


use ServiceEntity\Models\ServiceModel;

/**
 * Class ClientFavoriteDriver
 *
 * @package Src\Models\Client
 * @property int $client_favorite_driver
 * @property int|null $client_id
 * @property int|null $driver_id
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereClientFavoriteDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereDriverId($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereUpdatedAt($value)
 */
class ClientFavoriteDriver extends ServiceModel
{


    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'client_favorite_driver';
    /**
     * @var string
     */
    protected $primaryKey = 'client_favorite_driver_id';
    /**
     * @var string[]
     */
    protected $fillable = ['driver_id', 'client_id'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
}
