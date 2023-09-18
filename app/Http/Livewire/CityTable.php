<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class CityTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = City::class;

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;

    /**
     * @var string
     */
    public $buttonComponent = 'cities.table-components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('cities.created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            return [
                'class' => 'text-center',
            ];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '2') {
                return [
                    'class' => 'text-center',
                    'width' => '14%',

                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '2') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',

                ];
            }

            return [];
        });

        $this->setQueryStringStatus(false);

        $this->setFilterPillsStatus(false);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.city.city_name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.city.state_name'), 'state.name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(State::select('name')->whereColumn('state_id', 'states.id'), $direction);
                })
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('cities.table-components.action_button'),
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return City::with('state');
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            SelectFilter::make(__('messages.filter_name.state'))
                        ->options(
                            State::query()
                                    ->orderBy('name')
                                    ->get()
                                    ->keyBy('id')
                                    ->map(
                                        function ($country) {
                                            return $country->name;
                                        }
                                    )
                                    ->toArray()
                        )
                        ->filter(function (Builder $builder, $value) {
                            return $builder->where('state_id', $value);
                        }),
        ];
    }
}
