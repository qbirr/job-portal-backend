<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\FavouriteCompany;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_FavouriteCompany_C;

class FollowerRepository {
    public function getFollowers(Company $company): array|Collection|_IH_FavouriteCompany_C {
        return FavouriteCompany::with([
            'user',
            'user.candidate',
            'user.candidate.functionalArea',
            'user.candidate.careerLevel',
            'user.candidate.industry',
        ])->where(
            'company_id',
            $company->id
        )->select('favourite_companies.*')->get();
    }
}
