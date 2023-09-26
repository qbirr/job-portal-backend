<?php

namespace App\Repositories;

use App\Models\CareerLevel;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_CareerLevel_C;

/**
 * Class CareerLevelRepository
 *
 * @version July 7, 2020, 5:07 am UTC
 */
class CareerLevelRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'level_name',
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
        return CareerLevel::class;
    }

    public function fetch(): _IH_CareerLevel_C|Collection|array {
        return CareerLevel::select(['id', 'level_name'])->orderBy('id')->get();
    }
}
