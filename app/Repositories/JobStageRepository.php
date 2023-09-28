<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\JobStage;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_JobStage_C;

/**
 * Class JobStageRepository
 */
class JobStageRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'name',
        'description',
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
        return JobStage::class;
    }

    public function fetch(Company $company): Collection|array|_IH_JobStage_C {
        return JobStage::whereCompanyId($company->id)->select(['id', 'name', 'description'])->get();
    }
}
