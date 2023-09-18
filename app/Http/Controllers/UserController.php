<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laracasts\Flash\Flash;

/**
 * Class UserController
 */
class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @param  ChangePasswordRequest  $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $input = $request->all();

        try {
            $user = $this->userRepository->changePassword($input);

            return $this->sendSuccess(__('messages.flash.password_update'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * @param  UpdateUserProfileRequest  $request
     * @return JsonResponse
     */
    public function profileUpdate(UpdateUserProfileRequest $request)
    {
        $input = $request->all();

        try {
            $user = $this->userRepository->profileUpdate($input);

            return $this->sendResponse($user, __('messages.flash.profile_update'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * Show the form for editing the specified User.
     *
     * @return JsonResponse
     */
    public function editProfile()
    {
        $user = Auth::user();

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateLanguage(Request $request)
    {
        $language = $request->get('language');

        /** @var User $user */
        $user = getLoggedInUser();
        $user->update(['language' => $language]);

        return $this->sendSuccess(__('messages.flash.language_update'));
    }

    public function changeThemeMode()
    {
        $user = User::find(getLoggedInUser()->id);

        if ($user->theme_mode == User::LIGHT_MODE) {
            $user['theme_mode'] = User::DARK_MODE;
        } else {
            $user['theme_mode'] = User::LIGHT_MODE;
        }

        $user->update();

        return redirect(URL::previous());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex()
    {
        return view('admins.index');
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminCreate(Request $request)
    {
        return view('admins.create');
    }

    /**
     * @param  CreateAdminRequest  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminStore(CreateAdminRequest $request)
    {
        $input = $request->all();

        $admin = $this->userRepository->adminStore($input);

        Flash::success(__('messages.flash.admin_save'));

        return redirect(route('admin.index'));
    }

    /**
     * @param  User  $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminEdit(User $user)
    {
        if ($user->hasRole('Admin')) {
            $user->phone = preparePhoneNumber($user->phone, $user->region_code);
        } else {
            return view('errors.404');
        }

        return view('admins.edit', compact('user'));
    }

    /**
     * @param  User  $user
     * @param  UpdateAdminRequest  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminUpdate(User $user, UpdateAdminRequest $request)
    {
        $input = $request->all();

        $this->userRepository->updateAdmin($user, $input);

        Flash::success(__('messages.flash.admin_update'));

        return redirect(route('admin.index'));
    }

    /**
     * @param  User  $user
     * @return JsonResponse
     */
    public function adminDestroy(User $user): JsonResponse
    {
        $Models = [
            Candidate::class,
            Company::class,
            Job::class,
        ];
        $result = canDelete($Models, 'last_change', $user->id);
        if ($result) {
            return $this->sendError(__('messages.flash.admin_cant_delete'));
        }

        $result = canDelete([Transaction::class], 'approved_id', $user->id);
        if ($result) {
            return $this->sendError(__('messages.flash.admin_cant_delete'));
        }

        if ($user->hasRole('Admin')) {
            $user->delete();
        } else {
            return $this->sendError(__('messages.common.seems_message'));
        }

        return $this->sendSuccess(__('messages.flash.admin_delete'));
    }
}
