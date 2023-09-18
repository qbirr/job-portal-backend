<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\WebRegisterRequest;
use App\Repositories\WebRegisterRepository;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RegisterController extends AppBaseController
{
    /** @var WebRegisterRepository */
    private $webRegisterRepository;

    public function __construct(WebRegisterRepository $webRegisterRepository)
    {
        $this->webRegisterRepository = $webRegisterRepository;
    }

    /**
     * @return Factory|View
     */
    public function candidateRegister()
    {
        $isGoogleReCaptchaEnabled = $this->webRegisterRepository->getSettingForReCaptcha();

        return view('front_web.auth.candidate_register', compact('isGoogleReCaptchaEnabled'));
    }

    /**
     * @return Factory|View
     */
    public function employerRegister()
    {
        $isGoogleReCaptchaEnabled = $this->webRegisterRepository->getSettingForReCaptcha();

        return view('front_web.auth.employer_register', compact('isGoogleReCaptchaEnabled'));
    }

    /**
     * @param  WebRegisterRequest  $request
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function register(WebRegisterRequest $request)
    {
        $input = $request->all();
        $this->webRegisterRepository->store($input);
        $userType = ($input['type'] == 1) ? __('messages.notification_settings.candidate') : __('messages.company.employer');
        Flash::success(__('messages.flash.register_success_mail_active'));

        return $this->sendSuccess("{$userType} ".__('messages.flash.registration_done'));
    }
}
