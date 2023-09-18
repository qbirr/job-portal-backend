<?php

namespace App\Http\Livewire;

use App\Models\SalaryPeriod;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SalaryPeriodTable extends LivewireTableComponent
{
    protected $model = SalaryPeriod::class;

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;

    /**
     * @var string
     */
    public $buttonComponent = 'salary_periods.table-components.add_button';

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
            Column::make(__('messages.salary_period.period'), 'period')
                ->sortable()
                ->searchable()
                ->view('salary_periods.table-components.period'),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->view('salary_periods.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('salary_periods.table-components.action_button'),
        ];
    }
}
