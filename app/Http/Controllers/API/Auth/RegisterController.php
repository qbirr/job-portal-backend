<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ApiRegisterRequest;
use App\OpenApi\RequestBodies\UserCreateRequestBody;
use App\OpenApi\Responses\GlobalSuccessResponse;
use App\Repositories\WebRegisterRepository;
use Throwable;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[PathItem]
class RegisterController extends AppBaseController {
    public function __construct(
        private readonly WebRegisterRepository $repository,
    ) {}

    /**
     * @throws Throwable
     */
    #[Operation(tags: ['guest'])]
    #[RequestBody(factory: UserCreateRequestBody::class)]
    #[Response(factory: GlobalSuccessResponse::class)]
    public function register(ApiRegisterRequest $request) {
        $input = $request->all();
        $this->repository->store($input);
        $userType = ($input['type'] == 1) ? __('messages.notification_settings.candidate') : __('messages.company.employer');
        return $this->sendSuccess("{$userType} ".__('messages.flash.registration_done'));
    }
}
