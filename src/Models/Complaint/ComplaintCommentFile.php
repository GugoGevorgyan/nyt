<?php

namespace Src\Models\Complaint;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Complaint\ComplaintCommentFile
 *
 * @property int $complaint_comment_file_id
 * @property int $complaint_comment_id
 * @property string $file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ComplaintCommentFile newModelQuery()
 * @method static Builder|ComplaintCommentFile newQuery()
 * @method static Builder|ComplaintCommentFile query()
 * @method static Builder|ComplaintCommentFile whereComplaintCommentFileId($value)
 * @method static Builder|ComplaintCommentFile whereComplaintCommentId($value)
 * @method static Builder|ComplaintCommentFile whereCreatedAt($value)
 * @method static Builder|ComplaintCommentFile whereFile($value)
 * @method static Builder|ComplaintCommentFile whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ComplaintCommentFile extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'complaint_comment_files';
    /**
     * @var string
     */
    protected $primaryKey = 'complaint_comment_file_id';
    /**
     * @var array
     */
    protected $fillable = ['complaint_comment_id', 'file'];
}
