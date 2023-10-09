<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Bank
 *
 * @property int $id
 * @property string $name
 * @property string $acc_no
 * @property string $acc_name
 * @property string $swift_code
 * @property string|null $notes
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Bank newModelQuery()
 * @method static Builder|Bank newQuery()
 * @method static Builder|Bank query()
 * @method static Builder|Bank whereAccName($value)
 * @method static Builder|Bank whereAccNo($value)
 * @method static Builder|Bank whereCreatedAt($value)
 * @method static Builder|Bank whereId($value)
 * @method static Builder|Bank whereIsActive($value)
 * @method static Builder|Bank whereName($value)
 * @method static Builder|Bank whereNotes($value)
 * @method static Builder|Bank whereSwiftCode($value)
 * @method static Builder|Bank whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Bank extends Model {
    protected $fillable = [
        'name',
        'acc_no',
        'acc_name',
        'swift_code',
        'notes',
        'is_active',
    ];
}
