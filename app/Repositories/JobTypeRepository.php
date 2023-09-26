<?php

namespace App\Repositories;

use App\Models\JobType;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_JobType_C;

/**
 * Class JobTypeRepository
 *
 * @version June 22, 2020, 5:43 am UTC
 */
class JobTypeRepository extends BaseRepository {
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
        return JobType::class;
    }

    public function fetch(): _IH_JobType_C|Collection|array {
        return JobType::select(['id', 'name', 'description'])->get();
    }
}
