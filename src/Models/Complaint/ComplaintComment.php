<?php

namespace Src\Models\Complaint;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Src\Models\Complaint\ComplaintComment
 *
 * @property int $complaint_comment_id
 * @property int $complaint_id
 * @property int $worker_id
 * @property string|null $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Complaint $complaint
 * @property-read Collection|ComplaintCommentFile[] $files
 * @property-read int|null $files_count
 * @property-read SystemWorker $worker
 * @method static Builder|ComplaintComment newModelQuery()
 * @method static Builder|ComplaintComment newQuery()
 * @method static Builder|ComplaintComment query()
 * @method static Builder|ComplaintComment whereComplaintCommentId($value)
 * @method static Builder|ComplaintComment whereComplaintId($value)
 * @method static Builder|ComplaintComment whereCreatedAt($value)
 * @method static Builder|ComplaintComment whereText($value)
 * @method static Builder|ComplaintComment whereUpdatedAt($value)
 * @method static Builder|ComplaintComment whereWorkerId($value)
 * @mixin Eloquent
 */
class ComplaintComment extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'complaint_comments';
    /**
     * @var string
     */
    protected $primaryKey = 'complaint_comment_id';
    /**
     * @var array
     */
    protected $fillable = ['complaint_id', 'worker_id', 'text'];

    /**
     * @return BelongsTo
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'complaint_id');
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(ComplaintCommentFile::class, 'complaint_comment_id', 'complaint_comment_id');
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'worker_id', 'system_worker_id');
    }
}
