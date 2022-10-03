<?php

declare(strict_types=1);

namespace Src\Models\Franchise;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Order\PaymentType;
use Src\Models\Park;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class FranchiseTransactionObserver
 *
 * @package Src\Models\Franchise
 * @property int $franchise_transaction_id
 * @property string|null $number
 * @property int $franchise_id
 * @property int|null $park_id
 * @property int|null $worker_id
 * @property int|null $payment_type_id
 * @property int $side_id
 * @property string $side_type
 * @property int|null $second_side_id
 * @property string|null $second_side_type
 * @property int|null $reason_id
 * @property string|null $reason_type
 * @property int $type
 * @property string $franchise_cost
 * @property string $side_cost
 * @property string $amount
 * @property string $remainder
 * @property bool $out
 * @property bool $payed
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Franchise\Franchise $franchise
 * @property-read Park|null $park
 * @property-read PaymentType|null $payment_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $second_side
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $side
 * @property-read SystemWorker|null $worker
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereParkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction wherePayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereReasonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereRemainder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSecondSideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSecondSideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereWorkerId($value)
 * @mixin \Eloquent
 */
class FranchiseTransaction extends ServiceModel
{


    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'franchise_transactions';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_transaction_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'franchise_id',
        'park_id',
        'worker_id',
        'payment_type_id',
        'side_id',
        'side_type',
        'second_side_id',
        'second_side_type',
        'reason_id',
        'reason_type',
        'franchise_cost',
        'side_cost',
        'amount',
        'remainder',
        'out',
        'payed',
        'type',
        'number',
        'comment',
        'created_at'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['out' => 'boolean', 'payed' => 'boolean'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $last_number = (new static())::latest('franchise_transaction_id')->first(['franchise_transaction_id', 'number'])->number ?? '0000000001';
            ++$last_number;

            if (Str::startsWith($last_number, '0')) {
                preg_match('/[0]+/', $last_number, $matches);
                $new_number = $matches[0].($last_number);
                $new_number = \strlen($new_number) > 10 ? substr($new_number, \strlen($new_number) - 10) : $new_number;
            } else {
                $new_number = $last_number;
            }

            $transaction->number = $new_number;
            $transaction->created_at = now()->format('Y-m-d H:i:s.u');
        });
    }

    /**
     * @return BelongsTo
     */
    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'worker_id', 'system_worker_id');
    }

    /**
     * @return BelongsTo
     */
    public function payment_type(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'payment_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class, 'park_id', 'park_id');
    }

    /**
     * @return MorphTo
     */
    public function side(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function second_side(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function reason(): MorphTo
    {
        return $this->morphTo();
    }
}
