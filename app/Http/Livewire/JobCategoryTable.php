<?php

namespace App\Http\Livewire;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class JobCategoryTable extends LivewireTableComponent
{
    protected $model = JobCategory::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'job_categories.table_components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('is_featured')) {
                return [
                    'class' => 'd-flex justify-content-center',
                ];
            }

            if ($column->isField('id')) {
                return [
                    'class' => 'text-center p-5',
                ];
            }

            return [];
        });

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setQueryStringStatus(false);

        $this->setFilterPillsStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.job_category.job_category'), 'name')
                ->sortable()
                ->searchable()
                ->view('job_categories.table_components.name'),
            Column::make(__('messages.job_category.is_featured'), 'is_featured')
                ->sortable()
                ->view('job_categories.table_components.is_featured'),
            Column::make(__('messages.common.created_on'), 'created_at')
                ->sortable()
                ->view('job_categories.table_components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('job_categories.table_components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        $query = JobCategory::query()->select('job_categories.*')

        ->when($this->getAppliedFilterWithValue('job_categories.is_featured'), function ($query, $type) {
            return $query->where('job_categories.is_featured', $type);
        });

        return $query;
    }

    public function filters(): array
    {
        $jobCategoryMethod = JobCategory::FEATURED;

        return [
            SelectFilter::make(__('messages.common.status'))
                ->options($jobCategoryMethod)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('job_categories.is_featured', '=', $value);
                }),
        ];
    }
}
