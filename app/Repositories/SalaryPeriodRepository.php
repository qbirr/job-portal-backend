<?php

namespace App\Repositories;

use App\Models\SalaryPeriod;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_SalaryPeriod_C;

/**
 * Class SalaryPeriodRepository
 *
 * @version June 23, 2020, 5:43 am UTC
 */
class SalaryPeriodRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'period',
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
        return SalaryPeriod::class;
    }

    public function fetch(): array|Collection {
        return SalaryPeriod::select(['id', 'period', 'description'])->get();
    }
}
