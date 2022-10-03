<?php

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchisePhone;
use Src\Models\Franchise\FranchiseSubPhone;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerDispatcher;
use Src\Models\SystemUsers\WorkerOperator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\ClientMessage\ClientCall
 *
 * @property int $client_call_id
 * @property int $franchise_id
 * @property int|null $operator_id
 * @property int $callable_id
 * @property string $callable_type
 * @property string $call_start
 * @property string $call_end
 * @property int $call_duration
 * @property string $unix_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ClientCall newModelQuery()
 * @method static Builder|ClientCall newQuery()
 * @method static Builder|ClientCall query()
 * @method static Builder|ClientCall whereCallDuration($value)
 * @method static Builder|ClientCall whereCallEnd($value)
 * @method static Builder|ClientCall whereCallStart($value)
 * @method static Builder|ClientCall whereCallableId($value)
 * @method static Builder|ClientCall whereCallableType($value)
 * @method static Builder|ClientCall whereClientCallId($value)
 * @method static Builder|ClientCall whereCreatedAt($value)
 * @method static Builder|ClientCall whereFranchiseId($value)
 * @method static Builder|ClientCall whereOperatorId($value)
 * @method static Builder|ClientCall whereUnixTime($value)
 * @method static Builder|ClientCall whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $franchise_phone_id
 * @property int|null $franchise_sub_phone_id
 * @property int|null $system_worker_id
 * @property int|null $worker_operator_id
 * @property string $client_phone
 * @property-read Model|Eloquent $callable
 * @property-read Franchise $franchise
 * @property-read FranchisePhone $franchisePhone
 * @property-read FranchiseSubPhone|null $franchiseSubPhone
 * @property-read WorkerOperator|null $operator
 * @method static Builder|ClientCall whereClientPhone($value)
 * @method static Builder|ClientCall whereFranchisePhoneId($value)
 * @method static Builder|ClientCall whereFranchiseSubPhoneId($value)
 * @method static Builder|ClientCall whereSystemWorkerId($value)
 * @method static Builder|ClientCall whereWorkerOperatorId($value)
 * @property int $incoming
 * @property int $answered
 * @property-read mixed $call_date
 * @property-read mixed $call_time
 * @property-read mixed $duration_time
 * @property-read mixed $time_ago
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientCall whereAnswered($value)
 * @method static Builder|ClientCall whereIncoming($value)
 * @property int|null $workerable_id
 * @property string|null $workerable_type
 * @property-read Model|Eloquent $workerable
 * @method static Builder|ClientCall whereWorkerableId($value)
 * @method static Builder|ClientCall whereWorkerableType($value)
 * @property int|null $client_id
 * @property-read Client|null $client
 * @property-read SystemWorker|null $system_worker
 * @method static Builder|ClientCall whereClientId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ClientCall whereTimeAgo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class ClientCall extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'client_calls';

    /**
     * @var string
     */
    protected $primaryKey = 'client_call_id';

    protected $fillable = [
        'franchise_id',
        'franchise_phone_id',
        'franchise_sub_phone_id',
        'system_worker_id',
        'workerable_id',
        'workerable_type',
        'client_id',
        'call_start',
        'call_end',
        'call_duration',
        'client_phone',
        'answered',
        'incoming'
    ];

    protected $appends = ['time_ago', 'call_date', 'call_time', 'duration_time'];

    public function getTimeAgoAttribute()
    {
        if ($this->created_at) {
            return $this->attributes['time_ago'] = Carbon::parse($this->created_at)->diffForHumans();
        }
    }

    public function getCallDateAttribute()
    {
        if ($this->created_at) {
            return $this->attributes['call_date'] = $this->created_at->format('l, F j, Y');
        }
    }

    public function getCallTimeAttribute()
    {
        if ($this->created_at) {
            return $this->attributes['call_time'] = $this->created_at->format('G:i');
        }
    }

    public function getDurationTimeAttribute()
    {
        if ($this->created_at) {
            return $this->attributes['duration_time'] = gmdate('H:i:s', $this->call_duration);
        }
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
    public function franchisePhone(): BelongsTo
    {
        return $this->belongsTo(FranchisePhone::class, 'franchise_phone_id', 'franchise_phone_id');
    }

    /**
     * @return BelongsTo
     */
    public function franchiseSubPhone(): BelongsTo
    {
        return $this->belongsTo(FranchiseSubPhone::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    /**
     * @return MorphTo
     */
    public function workerable(): MorphTo
    {
        return $this->morphTo();
    }

    public function system_worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'system_worker_id', 'system_worker_id');
    }
}
