<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SubmissionStatus
 *
 * @property int $id
 * @property string $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|SubmissionStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubmissionStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubmissionStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubmissionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubmissionStatus whereStatusName($value)
 * @mixin \Eloquent
 */
class SubmissionStatus extends Model {
    public $timestamps = false;
}
