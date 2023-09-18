<?php

namespace App\Repositories;

use App\Models\FunctionalArea;

/**
 * Class FunctionalAreaRepository
 *
 * @version July 4, 2020, 7:26 am UTC
 */
class FunctionalAreaRepository extends BaseRepository
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
        return FunctionalArea::class;
    }
}
