<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\SubmissionLog
 *
 * @property int $id
 * @property int $company_id
 * @property int $submission_status_id
 * @property string|null $notes
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Company $company
 * @property-read SubmissionStatus|null $submissionStatus
 * @property-read User $user
 * @method static Builder|SubmissionLog newModelQuery()
 * @method static Builder|SubmissionLog newQuery()
 * @method static Builder|SubmissionLog query()
 * @method static Builder|SubmissionLog whereCompanyId($value)
 * @method static Builder|SubmissionLog whereCreatedAt($value)
 * @method static Builder|SubmissionLog whereId($value)
 * @method static Builder|SubmissionLog whereNotes($value)
 * @method static Builder|SubmissionLog whereSubmissionStatusId($value)
 * @method static Builder|SubmissionLog whereUpdatedAt($value)
 * @method static Builder|SubmissionLog whereUserId($value)
 * @mixin Eloquent
 */
class SubmissionLog extends Model {
    protected $fillable = [
        'company_id',
        'submission_status_id',
        'notes',
        'user_id',
    ];

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function submissionStatus(): BelongsTo {
        return $this->belongsTo(SubmissionStatus::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
