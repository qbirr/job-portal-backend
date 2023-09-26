<?php

namespace App\Repositories;

use App\Models\SalaryCurrency;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SalaryCurrencyRepository
 *
 * @version July 7, 2020, 6:41 am UTC
 */
class SalaryCurrencyRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'currency_name',
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
        return SalaryCurrency::class;
    }

    public function fetch(): Collection|array {
        return SalaryCurrency::select(['id', 'currency_name', 'currency_icon', 'currency_code'])->get();
    }
}
