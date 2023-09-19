<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $conversions_disk
 * @property string|null $uuid
 * @property array $generated_conversions
 * @property-read \App\Models\Candidate|null $candidate
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia newQuery()
 * @method static Builder|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMedia whereUuid($value)
 * @mixin \Eloquent
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
