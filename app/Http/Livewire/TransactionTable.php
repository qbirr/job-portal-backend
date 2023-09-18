<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TransactionTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = Transaction::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField('invoice_id') == 'invoice_id') {
                return[
                    'class' => 'text-center',
                ];
            }
            if ($column->isField('amount')) {
                return [
                    'style' => 'display: flex; justify-content: flex-end',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '6') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',

                ];
            }
            if ($columnIndex == '3') {
                return [
                    'style' => 'text-align: end',
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
            Column::make(__('messages.transaction.type'), 'owner_type')
                ->sortable()
                ->view('transactions.table-components.owner_type'),
            Column::make(__('messages.transaction.user_name'), 'user.first_name')
                ->searchable(),
            Column::make(__('messages.transaction.transaction_date'), 'created_at')
                ->sortable()
                ->searchable()
                ->view('transactions.table-components.created_at'),
            Column::make(__('messages.plan.amount'), 'amount')
                ->sortable()
                ->searchable()
                ->view('transactions.table-components.amount'),
            Column::make(__('messages.transaction.payment_approved'), 'is_approved')
                ->searchable()
                ->view('transactions.table-components.transaction-approved'),
            Column::make(__('messages.common.status'), 'status')
            ->sortable()
            ->view('transactions.table-components.status'),
            Column::make(__('messages.transaction.invoice'), 'invoice_id')
                ->view('transactions.table-components.action_button'),
            Column::make(__('messages.common.approved_by'), 'approved_id')
                ->searchable()
                ->view('transactions.table-components.transaction-approved-by'),
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        if (Auth::user()->hasRole('Admin')) {
            $query = Transaction::query();
            if ($query->where('transactions.owner_type', Subscription::class)->exists()) {
                $query->with('type', 'user', 'admin')->select('transactions.*');
            } else {
                $query->with('type', 'user', 'admin')->select('transactions.*');
            }
        }

        if (Auth::user()->hasRole('Employer')) {
            $query = Transaction::where('user_id', getLoggedInUserId())->get();

            foreach ($query as $row) {
                if ($row->owner_type == Subscription::class) {
                    $row->load('type.planCurrency.salaryCurrency');
                }
            }
        }

        return $query;
    }
}
