<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AdminTable extends LivewireTableComponent
{
    protected $model = User::class;

    public $showButtonOnHeader = true;

    public $isSuperAdmin = false;

    public $buttonComponent = 'admins.table_components.add_button';

    protected $listeners = ['refresh' => '$refresh', 'changeStatus'];

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

        $this->setFilterPillsStatus(false);

        $this->setQueryStringStatus(false);
    }

    public function columns(): array
    {
        $this->isSuperAdmin = getSuperAdmin()->id == getLoggedInUserId();
        if ($this->isSuperAdmin) {
            $columnsArr = [
                Column::make(__('messages.common.name'), 'first_name')
                    ->sortable()
                    ->searchable()
                    ->view('admins.table_components.name_email'),
                Column::make(__('messages.setting.phone'), 'phone')
                    ->sortable()
                    ->searchable()
                    ->view('admins.table_components.phone'),
                Column::make(__('messages.common.status'), 'is_active')
                    ->view('admins.table_components.status'),
                Column::make(__('messages.common.action'), 'id')
                    ->view('admins.table_components.action_button'),
            ];
        } else {
            $columnsArr = [
                Column::make(__('messages.common.admin_name'), 'first_name')
                    ->sortable()
                    ->searchable()
                    ->view('admins.table_components.name_email'),
                Column::make(__('messages.setting.phone'), 'phone')
                    ->sortable()
                    ->searchable()
                    ->view('admins.table_components.phone'),
            ];
        }

        return $columnsArr;
    }

    public function builder(): Builder
    {
        return User::where('id', '!=', Auth::user()->id)->whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->select('users.*');
    }

    public function changeStatus($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => ! (bool) $user->is_active,
        ]);
    }
}
