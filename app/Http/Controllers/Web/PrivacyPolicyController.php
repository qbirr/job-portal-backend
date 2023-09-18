<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

/**
 * Class PrivacyPolicyController
 */
class PrivacyPolicyController extends AppBaseController
{
    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function showPrivacyPolicy()
    {
        $privacyPolicy = Setting::where('key', '=', 'privacy_policy')->get()->toArray();

        return view('front_web.privacy_policy.index', compact('privacyPolicy'));
    }

    /**
     * @return Application
     */
    public function showTermsConditions()
    {
        $termsConditions = Setting::where('key', '=', 'terms_conditions')->get()->toArray();

        return view('front_web.terms_conditions.index', compact('termsConditions'));
    }
}
