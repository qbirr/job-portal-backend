<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_City_C;

/**
 * Class CityRepository
 *
 * @version July 7, 2020, 5:07 am UTC
 */
class CityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'name',
        'state_id',
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
        return City::class;
    }

    public function fetch(): Collection|_IH_City_C|array {
        return City::all(['id', 'state_id', 'name']);
    }
}
