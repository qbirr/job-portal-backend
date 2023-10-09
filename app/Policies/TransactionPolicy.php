<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Transaction $transaction): bool {
        return $user->hasRole('Admin') || $transaction->user_id == $user->id;
    }

    public function create(User $user): bool {
        return $user->hasRole('Employer');
    }

    public function update(User $user, Transaction $transaction): bool {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Transaction $transaction): bool {
        return $user->hasRole('Admin');
    }

    public function restore(User $user, Transaction $transaction): bool {
        return $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Transaction $transaction): bool {
        return $user->hasRole('Admin');
    }
}
