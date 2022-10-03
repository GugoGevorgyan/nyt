<?php

declare(strict_types=1);

namespace Src\Models\Terminal;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Debt\Debt;
use Src\Models\Driver\DriverWallet;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class DebtRepayment
 *
 * @package Src\Models\Terminal
 * @property int $debt_repayment_id
 * @property int|null $amount
 * @property string|null $min_debt
 * @property string|null $max_debt
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DebtRepayment newModelQuery()
 * @method static Builder|DebtRepayment newQuery()
 * @method static Builder|DebtRepayment query()
 * @method static Builder|DebtRepayment whereAmount($value)
 * @method static Builder|DebtRepayment whereDebtRepaymentId($value)
 * @method static Builder|DebtRepayment whereMaxDebt($value)
 * @method static Builder|DebtRepayment whereMinDebt($value)
 * @mixin Eloquent
 * @property-read Collection|Debt[] $debt
 * @property-read int|null $debt_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class DebtRepayment extends ServiceModel
{


    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'debt_repayment';
    /**
     * @var string
     */
    protected $primaryKey = 'debt_repayment_id';
    /**
     * @var string[]
     */
    protected $fillable = ['amount', 'min_debt', 'max_debt'];

    /**
     * @return HasMany
     */
    public function debt(): HasMany
    {
        return $this->hasMany(DriverWallet::class, 'repayment_id', $this->primaryKey);
    }
}
