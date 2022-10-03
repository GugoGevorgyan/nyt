<?php

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * Src\Models\Driver\FranchiseDriver
 *
 * @property int $driver_franchisee_id
 * @property int|null $franchise_id
 * @property int|null $driver_id
 * @property int|null $type_id
 * @property int|null $graphic_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DriverGraphic|null $graphic
 * @method static Builder|FranchiseDriver newModelQuery()
 * @method static Builder|FranchiseDriver newQuery()
 * @method static Builder|FranchiseDriver query()
 * @method static Builder|FranchiseDriver whereCreatedAt($value)
 * @method static Builder|FranchiseDriver whereDriverFranchiseeId($value)
 * @method static Builder|FranchiseDriver whereDriverId($value)
 * @method static Builder|FranchiseDriver whereFranchiseId($value)
 * @method static Builder|FranchiseDriver whereGraphicId($value)
 * @method static Builder|FranchiseDriver whereTypeId($value)
 * @method static Builder|FranchiseDriver whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FranchiseDriver extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'franchise_driver';

    /**
     * @var string
     */
    protected $primaryKey = 'driver_franchisee_id';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function graphic(): BelongsTo
    {
        return $this->belongsTo(DriverGraphic::class, 'graphic_id');
    }

}
