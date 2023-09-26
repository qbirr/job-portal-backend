<?php

namespace App\Repositories;

use App\Models\FunctionalArea;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FunctionalAreaRepository
 *
 * @version July 4, 2020, 7:26 am UTC
 */
class FunctionalAreaRepository extends BaseRepository {
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
        return FunctionalArea::class;
    }

    public function fetch(): Collection|array {
        return FunctionalArea::select(['id', 'name'])->orderBy('id')->get();
    }
}
