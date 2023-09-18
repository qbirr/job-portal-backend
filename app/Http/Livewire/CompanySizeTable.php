<?php

namespace App\Http\Livewire;

use App\Models\CompanySize;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CompanySizeTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = CompanySize::class;

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;

    /**
     * @var string
     */
    public $buttonComponent = 'company_sizes.table-components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('size')) {
                return[
                    'style' => 'width:50%',
                ];
            }

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
            Column::make(__('messages.company_size.size'), 'size')->searchable(),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->view('company_sizes.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('company_sizes.table-components.action_button'),
        ];
    }
}
