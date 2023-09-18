<?php

namespace App\Http\Livewire;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class SelectedCandidateTable extends LivewireTableComponent
{
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

        $this->setQueryStringStatus(false);

        $this->setFilterPillsStatus(false);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.company.candidate_name'), 'candidate.user.first_name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(User::select('first_name')->whereColumn('candidates.user_id', 'users.id'), $direction);
                })
                ->searchable()
                ->view('selected_candidate.table-components.candidate_first_name'),
            Column::make(__('messages.company.employer_name'), 'job.company.user.first_name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(User::select('first_name')->whereColumn('companies.user_id', 'users.id'), $direction);
                })
                ->searchable()
                ->view('selected_candidate.table-components.employer_first_name'),
            Column::make(__('messages.common.status'), 'status')
                ->view('selected_candidate.table-components.status'),
            Column::make(__('messages.job.job_details'), 'id')
                ->view('selected_candidate.table-components.action_button'),
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return JobApplication::with('job.company.user', 'candidate')
            ->whereIn('job_applications.status', [JobApplication::SHORT_LIST, JobApplication::COMPLETE])
//            ->leftJoin('jobs','job_applications.job_id','=','jobs.id')
//            ->leftJoin('companies','jobs.company_id','=','companies.id')
//            ->leftJoin('users', function ($join) {
//                $join->on('companies.user_id', '=', 'users.id');
//            })
            ->select('job_applications.*');
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            SelectFilter::make(__('messages.common.status'))
                ->options([
                    '' =>  (__('messages.filter_name.select_status')),
                    1 => 'Hired',
                    2 => 'Ongoing',
                ])
                ->filter(
                    function (Builder $builder, string $value) {
                        if ($value == 1) {
                            $builder->where('job_applications.status', '=', 3);
                        } else {
                            $builder->where('job_applications.status', '=', 4);
                        }
                    }
                ),
        ];
    }
}
