<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\CmsServices
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices query()
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CmsServices whereValue($value)
 * @mixin \Eloquent
 */
class CmsServices extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var string
     */
    public $table = 'cms_services';

    public const PATH = 'settings';

    public $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'key' => 'string',
        'value' => 'string',
    ];
}
