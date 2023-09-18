<?php

namespace App\Http\Livewire;

use App\Models\RequiredDegreeLevel;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DegreeLevelTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = RequiredDegreeLevel::class;

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;

    /**
     * @var string
     */
    public $buttonComponent = 'required_degree_levels.table-components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

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
            if ($column->isField('name')) {
                return [
                    'width' => '70%',
                ];
            }
            if ($columnIndex == '2') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',

                ];
            }

            return [];
        });

        $this->setQueryStringStatus(false);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.required_degree_level.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->searchable()
                ->view('required_degree_levels.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('required_degree_levels.table-components.action_button'),
        ];
    }
}
