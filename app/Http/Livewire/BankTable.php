<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class BankTable extends LivewireTableComponent {
    protected $model = Bank::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'banks.table-components.add_button';

    protected $listeners = ['refresh' => '$refresh' ];

    public function configure(): void {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('id', 'asc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('name')) {
                return [
                    'style' => 'width:15%',
                ];
            }
            if ($column->isField('notes')) {
                return [
                    'style' => 'white-space: nowrap;  overflow: hidden;  text-overflow: ellipsis;',
                ];
            }

            return[
                'class' => 'text-center',
            ];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '5') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',

                ];
            }
            if ($columnIndex == '2') {
                return [
                    'style' => 'text-align: end',
                ];
            }

            return [];
        });

        $this->setQueryStringStatus(false);
    }

    public function columns(): array {
        return [
            Column::make(__('messages.skill.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bank.acc_no'), 'acc_no')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bank.acc_name'), 'acc_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bank.swift_code'), 'swift_code')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bank.notes'), 'notes')
                ->view('banks.table-components.notes'),
            Column::make(__('messages.common.status'), 'is_active')
                ->view('banks.table-components.status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('banks.table-components.action_button'),
        ];
    }

    public function builder(): Builder {
        return Bank::query();
    }

    public function filters(): array {
        return [
            SelectFilter::make(__('messages.common.status'))
                ->options([
                    '' => (__('messages.filter_name.select_status')),
                    1 => (__('messages.common.active')),
                    2 => (__('messages.common.de_active')),
                ])
                ->filter(
                    function (Builder $builder, string $value) {
                        if ($value == 1) {
                            $builder->where('banks.is_active', '=', 1);
                        } else {
                            $builder->where('banks.is_active', '=', 0);
                        }
                    }
                ),
        ];
    }
}
