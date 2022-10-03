<?php

declare(strict_types=1);

namespace Src\Models\Secure;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Firewall
 *
 * @property int $firewall_id
 * @property int $ip
 * @property int $blocked
 * @property string|null $url
 * @property string $created_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Firewall newModelQuery()
 * @method static Builder|Firewall newQuery()
 * @method static Builder|Firewall query()
 * @method static Builder|Firewall whereBlocked($value)
 * @method static Builder|Firewall whereCreatedAt($value)
 * @method static Builder|Firewall whereFirewallId($value)
 * @method static Builder|Firewall whereIp($value)
 * @method static Builder|Firewall whereUrl($value)
 * @mixin Eloquent
 */
class Firewall extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'firewall';
    /**
     * @var string
     */
    protected $primaryKey = 'firewall_id';
    /**
     * @var string[]
     */
    protected $fillable = ['ip', 'block', 'url'];
}
