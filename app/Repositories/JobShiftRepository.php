<?php

namespace App\Repositories;

use App\Models\JobShift;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_JobShift_C;

/**
 * Class JobShiftRepository
 *
 * @version June 23, 2020, 5:43 am UTC
 */
class JobShiftRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'shift',
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
        return JobShift::class;
    }

    public function fetch(): array|Collection|_IH_JobShift_C {
        return JobShift::select(['id', 'shift', 'description'])->get();
    }
}
