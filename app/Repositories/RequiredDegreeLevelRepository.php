<?php

namespace App\Repositories;

use App\Models\RequiredDegreeLevel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequiredDegreeLevelRepository
 *
 * @version June 30, 2020, 12:30 pm UTC
 */
class RequiredDegreeLevelRepository extends BaseRepository {
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
        return RequiredDegreeLevel::class;
    }

    public function fetch(): Collection|array {
        return RequiredDegreeLevel::select(['id', 'name'])->orderBy('id')->get();
    }
}
