<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property string $invoice_id
 * @property float|null $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Subscription $subscription
 * @property-read User $user
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereInvoiceId($value)
 * @method static Builder|Transaction whereOwnerId($value)
 * @method static Builder|Transaction whereOwnerType($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static Builder|Transaction whereUserId($value)
 * @property int $owner_id
 * @property string $owner_type
 * @property-read mixed $type_name
 * @property-read Model|Eloquent $type
 * @property int $status
 * @property int $is_approved
 * @property int|null $approved_id
 * @property int|null $plan_currency_id
 * @property-read User|null $admin
 * @property-read Subscription|null $owner
 * @property-read SalaryCurrency|null $salaryCurrency
 * @method static Builder|Transaction whereApprovedId($value)
 * @method static Builder|Transaction whereIsApproved($value)
 * @method static Builder|Transaction wherePlanCurrencyId($value)
 * @method static Builder|Transaction whereStatus($value)
 * @property int|null $bank_id
 * @property string|null $image_uri
 * @method static Builder|Transaction whereBankId($value)
 * @method static Builder|Transaction whereImageUri($value)
 * @property-read Bank|null $bank
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Media|null $latestMedia
 * @mixin Eloquent
 */
class Transaction extends Model implements HasMedia {
    use InteractsWithMedia;

    public const POP_PATH = 'proof_of_payments';

    /**
     * @var string
     */
    public $table = 'transactions';

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'owner_id',
        'owner_type',
        'amount',
        'invoice_id',
        'bank_id',
        'status',
        'image_uri',
        'is_approved',
        'approved_id',
        'plan_currency_id',
    ];

    const  DIGITAL = 1;

    const  MANUALLY = 2;

    const STATUS = [
        self::DIGITAL => 'digital',
        self::MANUALLY => 'Manually',
    ];

    const PENDING = 0;

    const APPROVED = 1;

    const REJECTED = 2;

    /**
     * @var array
     */
    public $casts = [
        'user_id' => 'integer',
        'owner_id' => 'integer',
        'amount' => 'float',
        'invoice_id' => 'string',
        'owner_type' => 'string',
        'is_approved' => 'integer',
        'status' => 'integer',
        'approved_id' => 'integer',
        'plan_currency_id' => 'integer',
    ];

    protected $appends = ['type_name'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(Subscription::class, 'owner_id');
    }

    public function type(): MorphTo {
        return $this->morphTo('owner');
    }

    public function admin(): HasOne {
        return $this->hasOne(User::class, 'id', 'approved_id');
    }

    public function bank(): BelongsTo {
        return $this->belongsTo(Bank::class);
    }

    public function getTypeNameAttribute(): string {
        switch ($this->owner_type) {
            case Company::class:
                return 'Featured Company';
                break;
            case Job::class:
                return 'Featured Job';
                break;
            case Subscription::class:
                return 'Company Subscription';
                break;
            default:
                return 'N/A';
        }
    }

    public function salaryCurrency(): BelongsTo {
        return $this->belongsTo(SalaryCurrency::class, 'plan_currency_id', 'id');
    }

    public function latestMedia(): MorphOne {
        return $this->morphOne(config('media-library.media_model'), 'model')->latestOfMany();
    }
}
