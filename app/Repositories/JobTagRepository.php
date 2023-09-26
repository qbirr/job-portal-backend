<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Tag_C;

/**
 * Class JobTagRepository
 *
 * @version June 22, 2020, 5:43 am UTC
 */
class JobTagRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'name',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string {
        return Tag::class;
    }

    public function fetch(): _IH_Tag_C|Collection|array {
        return Tag::select(['id', 'name', 'description'])->get();
    }
}
