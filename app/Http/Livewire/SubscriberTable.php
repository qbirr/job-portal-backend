<?php

namespace App\Http\Livewire;

use App\Models\NewsLetter;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SubscriberTable extends LivewireTableComponent
{
    protected $model = NewsLetter::class;

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

    public function columns(): array
    {
        return [
            Column::make(__('messages.user.email'), 'email')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->view('subscribers.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('subscribers.table-components.action_button'),
        ];
    }
}
