<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, JobApplication $jobApplication): bool {
        return $jobApplication->job->company->user->id == $user->id || $jobApplication->candidate->user->id == $user->id;
    }

    public function create(User $user): bool {
        return $user->company->exists();
    }

    public function update(User $user, JobApplication $jobApplication): bool {
        return $jobApplication->job->company->user->id == $user->id;
    }

    public function delete(User $user, JobApplication $jobApplication): bool {
        return $jobApplication->job->company->user->id == $user->id;
    }

    public function restore(User $user, JobApplication $jobApplication): bool {
        return $jobApplication->job->company->user->id == $user->id;
    }

    public function forceDelete(User $user, JobApplication $jobApplication): bool {
        return false;
    }
}
