<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\CustomMedia
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $responsive_images
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $conversions_disk
 * @property string|null $uuid
 * @property array $generated_conversions
 * @property-read Candidate|null $candidate
 * @property-read Model|Eloquent $model
 * @method static MediaCollection<int, static> all($columns = ['*'])
 * @method static MediaCollection<int, static> get($columns = ['*'])
 * @method static Builder|CustomMedia newModelQuery()
 * @method static Builder|CustomMedia newQuery()
 * @method static Builder|Media ordered()
 * @method static Builder|CustomMedia query()
 * @method static Builder|CustomMedia whereCollectionName($value)
 * @method static Builder|CustomMedia whereConversionsDisk($value)
 * @method static Builder|CustomMedia whereCreatedAt($value)
 * @method static Builder|CustomMedia whereCustomProperties($value)
 * @method static Builder|CustomMedia whereDisk($value)
 * @method static Builder|CustomMedia whereFileName($value)
 * @method static Builder|CustomMedia whereGeneratedConversions($value)
 * @method static Builder|CustomMedia whereId($value)
 * @method static Builder|CustomMedia whereManipulations($value)
 * @method static Builder|CustomMedia whereMimeType($value)
 * @method static Builder|CustomMedia whereModelId($value)
 * @method static Builder|CustomMedia whereModelType($value)
 * @method static Builder|CustomMedia whereName($value)
 * @method static Builder|CustomMedia whereOrderColumn($value)
 * @method static Builder|CustomMedia whereResponsiveImages($value)
 * @method static Builder|CustomMedia whereSize($value)
 * @method static Builder|CustomMedia whereUpdatedAt($value)
 * @method static Builder|CustomMedia whereUuid($value)
 * @method static MediaCollection<int, static> all($columns = ['*'])
 * @method static MediaCollection<int, static> get($columns = ['*'])
 * @mixin Eloquent
 */
class CustomMedia extends Media
{
    /**
     * @return BelongsTo
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'model_id');
    }
}
