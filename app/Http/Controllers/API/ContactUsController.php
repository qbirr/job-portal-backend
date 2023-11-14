<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ContactFormRequest;
use App\Repositories\WebHomeRepository;

class ContactUsController extends AppBaseController {
    public function __construct(
        private readonly WebHomeRepository $homeRepository
    ) {
    }

    public function __invoke(ContactFormRequest $request) {
        $this->homeRepository->storeInquires($request->all());
        return $this->sendSuccess('Message inquiry sent successfully.');
    }
}
