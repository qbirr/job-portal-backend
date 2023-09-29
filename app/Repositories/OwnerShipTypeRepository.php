<?php

namespace App\Repositories;

use App\Models\OwnerShipType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OwnerShipTypeRepository
 *
 * @version June 22, 2020, 9:47 am UTC
 */
class OwnerShipTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
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
        return OwnerShipType::class;
    }

    public function fetch(): Collection|array {
        return OwnerShipType::get(['id', 'name', 'description', 'is_default']);
    }
}
