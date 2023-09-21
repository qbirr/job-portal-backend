<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CompanyApprovedMiddleware {
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && Auth::user()->is_active && auth()->user()->role('Employer')) {
            /** @var Company $company */
            $company = auth()->user()->company;
            logger($company->submissionStatus->status_name);
            switch ($company->submission_status_id) {
                case 2:
                    break;
                case 3:
                    flash('Your company verification rejected! ' . $company->lastSubmissionLog->notes)->error();
                    break;
                default:
                    flash('Your company still waiting admin verification')->warning();
                    break;
            }
        }
        return $next($request);
    }
}
