<?php

declare(strict_types=1);

namespace Src\Models\Debt;

use Illuminate\Database\Eloquent\Model;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Debt\DebtTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes query()
 * @mixin \Eloquent
 * @property int $debt_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereDebtTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereUpdatedAt($value)
 */
class DebtTypes extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'debt_types';
    /**
     * @var string
     */
    protected $primaryKey = 'debt_type_id';
    /**
     * @var string[]
     */
    protected $fillable = ['name'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     *
     */
    public function debts()
    {
        $this->hasMany(Debt::class,'tpye','debt_type_id');
    }


    /**
     *
     */
    public function current_debt()
    {
        $this->morphTo('current_debt','debtor_type','debtor_id');
    }
}
