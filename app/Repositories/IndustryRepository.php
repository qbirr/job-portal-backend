<?php

namespace App\Repositories;

use App\Models\Industry;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Industry_C;

/**
 * Class IndustryRepository
 *
 * @version June 20, 2020, 5:43 am UTC
 */
class IndustryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Industry::class;
    }

    public function fetch(): Collection|_IH_Industry_C|array {
        return Industry::get(['id', 'name', 'description', 'is_default']);
    }
}
