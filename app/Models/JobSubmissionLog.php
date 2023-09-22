<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobSubmissionLog
 *
 * @property int $id
 * @property int $job_id
 * @property int $submission_status_id
 * @property string|null $notes
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Job|null $job
 * @property-read SubmissionStatus|null $submissionStatus
 * @property-read User|null $user
 * @method static Builder|JobSubmissionLog newModelQuery()
 * @method static Builder|JobSubmissionLog newQuery()
 * @method static Builder|JobSubmissionLog query()
 * @method static Builder|JobSubmissionLog whereCreatedAt($value)
 * @method static Builder|JobSubmissionLog whereId($value)
 * @method static Builder|JobSubmissionLog whereJobId($value)
 * @method static Builder|JobSubmissionLog whereNotes($value)
 * @method static Builder|JobSubmissionLog whereSubmissionStatusId($value)
 * @method static Builder|JobSubmissionLog whereUpdatedAt($value)
 * @method static Builder|JobSubmissionLog whereUserId($value)
 * @mixin Eloquent
 */
class JobSubmissionLog extends Model {
    protected $fillable = [
        'job_id',
        'submission_status_id',
        'notes',
        'user_id',
    ];

    public function job(): BelongsTo {
        return $this->belongsTo(Job::class);
    }

    public function submissionStatus(): BelongsTo {
        return $this->belongsTo(SubmissionStatus::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
