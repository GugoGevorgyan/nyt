<?php

namespace Src\Models\Complaint;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Complaint\ComplaintFile
 *
 * @property int $complaint_file_id
 * @property int $complaint_id
 * @property string $file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ComplaintFile newModelQuery()
 * @method static Builder|ComplaintFile newQuery()
 * @method static Builder|ComplaintFile query()
 * @method static Builder|ComplaintFile whereComplaintFileId($value)
 * @method static Builder|ComplaintFile whereComplaintId($value)
 * @method static Builder|ComplaintFile whereCreatedAt($value)
 * @method static Builder|ComplaintFile whereFile($value)
 * @method static Builder|ComplaintFile whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ComplaintFile extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'complaint_files';
    /**
     * @var string
     */
    protected $primaryKey = 'complaint_file_id';
    /**
     * @var array
     */
    protected $fillable = ['complaint_id', 'file'];
}
