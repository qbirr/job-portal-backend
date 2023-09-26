<?php

namespace App\Repositories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SkillRepository
 *
 * @version June 22, 2020, 5:43 am UTC
 */
class SkillRepository extends BaseRepository {
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
        return Skill::class;
    }

    public function fetch(): array|Collection {
        return Skill::select(['id', 'name', 'description'])->get();
    }
}
