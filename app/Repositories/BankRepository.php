<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Bank_C;

class BankRepository {
    public function create($input) {
        return Bank::create($input);
    }

    public function update(Bank $bank, $input): bool {
        return $bank->update($input);
    }

    public function delete(Bank $bank): ?bool {
        return $bank->delete();
    }

    public function fetch(): Collection|array|_IH_Bank_C {
        return Bank::all();
    }
}
