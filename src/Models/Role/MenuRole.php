<?php

declare(strict_types=1);

namespace Src\Models\Role;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Views\MenuRole
 *
 * @property int $menu_role_id
 * @property int|null $role_id
 * @property int|null $menu_id
 * @method static Builder|MenuRole newModelQuery()
 * @method static Builder|MenuRole newQuery()
 * @method static Builder|MenuRole query()
 * @method static Builder|MenuRole whereMenuId($value)
 * @method static Builder|MenuRole whereMenuRoleId($value)
 * @method static Builder|MenuRole whereRoleId($value)
 * @mixin Eloquent
 * @property int|null $permission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|MenuRole whereCreatedAt($value)
 * @method static Builder|MenuRole wherePermissionId($value)
 */
class MenuRole extends ServiceModel
{


    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'menu_role';
    /**
     * @var string
     */
    protected $primaryKey = 'menu_role_id';
    /**
     * @var array
     */
    protected $fillable = ['menu_id', 'role_id', 'permission_id'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
}
