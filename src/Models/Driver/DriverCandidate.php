<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\Views\Image;

/**
 * Src\Models\Driver\DriverCandidate
 *
 * @property int $id
 * @property int|null $tutor_id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string|null $email
 * @property string $driver_license_info
 * @property string $driver_license_category
 * @property string $passport_info
 * @property string $learn_status
 * @property string $experience
 * @property int $raiting
 * @property string $image
 * @property int $penalty
 * @property int $driver_license_revocation
 * @property string $registration_date
 * @property string|null $learn_start
 * @property string|null $learn_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriverCandidate newModelQuery()
 * @method static Builder|DriverCandidate newQuery()
 * @method static Builder|DriverCandidate query()
 * @method static Builder|DriverCandidate whereCreatedAt($value)
 * @method static Builder|DriverCandidate whereDriverLicenseCategory($value)
 * @method static Builder|DriverCandidate whereDriverLicenseInfo($value)
 * @method static Builder|DriverCandidate whereDriverLicenseRevocation($value)
 * @method static Builder|DriverCandidate whereEmail($value)
 * @method static Builder|DriverCandidate whereExperience($value)
 * @method static Builder|DriverCandidate whereId($value)
 * @method static Builder|DriverCandidate whereImage($value)
 * @method static Builder|DriverCandidate whereLearnEnd($value)
 * @method static Builder|DriverCandidate whereLearnStart($value)
 * @method static Builder|DriverCandidate whereLearnStatus($value)
 * @method static Builder|DriverCandidate whereName($value)
 * @method static Builder|DriverCandidate wherePassportInfo($value)
 * @method static Builder|DriverCandidate wherePenalty($value)
 * @method static Builder|DriverCandidate wherePhone($value)
 * @method static Builder|DriverCandidate whereRaiting($value)
 * @method static Builder|DriverCandidate whereRegistrationDate($value)
 * @method static Builder|DriverCandidate whereSurname($value)
 * @method static Builder|DriverCandidate whereTutorId($value)
 * @method static Builder|DriverCandidate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver_candidate_id
 * @property string|null $deleted_at
 * @property-read Driver $driver
 * @property-read DriverInfo $info
 * @property-read SystemWorker|null $tutor
 * @method static Builder|DriverCandidate whereDeletedAt($value)
 * @method static Builder|DriverCandidate whereDriverCandidateId($value)
 * @property int|null $driver_info_id
 * @property int|null $franchise_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|DriverCandidate whereDriverInfoId($value)
 * @method static Builder|DriverCandidate whereFranchiseId($value)
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate withoutTrashed()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read LearnStatus $learnStatus
 * @property int $learn_status_id
 * @method static Builder|DriverCandidate whereLearnStatusId($value)
 * @property-read DriverContract|null $driver_active_contract
 * @property-read DriverContract|null $driver_contract
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class DriverCandidate extends ServiceModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'driver_candidates';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_candidate_id';
    /**
     * @var array
     */
    protected $fillable = [
        'tutor_id',
        'driver_info_id',
        'franchise_id',
        'phone',
        'learn_status_id',
        'learn_start',
        'learn_end',
    ];

    /**
     * @return BelongsTo
     */
    public function learnStatus(): BelongsTo
    {
        return $this->belongsTo(LearnStatus::class, 'learn_status_id', 'learn_status_id');
    }

    /**
     * @return BelongsTo
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'tutor_id', 'system_worker_id');
    }

    /**
     * @return HasOneThrough
     */
    public function driver(): HasOneThrough
    {
        return $this->hasOneThrough(Driver::class, DriverInfo::class, 'driver_info_id', 'driver_info_id');
    }

    /**
     * @return HasOneThrough
     */
    public function driver_active_contract(): HasOneThrough
    {
        return $this->hasOneThrough(DriverContract::class, Driver::class, 'driver_id', 'driver_contract_id')
            ->where('active', '=', true)
            ->where('signed', '=', true);
    }

    /**
     * @return HasOneThrough
     */
    public function driver_contract(): HasOneThrough
    {
        return $this->hasOneThrough(DriverContract::class, Driver::class, 'driver_id', 'driver_contract_id');
    }

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'candidate_img', 'imageable_type', 'imageable_id', 'driver_candidate_id');
    }

    /**
     * @return BelongsTo
     */
    public function info(): BelongsTo
    {
        return $this->belongsTo(DriverInfo::class, 'driver_info_id', 'driver_info_id');
    }

    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone): void
    {
        $this->attributes['phone'] = preg_replace('/[\D]/', '', (string)$phone);
    }
}
