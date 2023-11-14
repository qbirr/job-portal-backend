<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\CmsServices;
use App\Models\FAQ;

class AboutUsController extends AppBaseController {
    public function __invoke() {
        $faqLists = FAQ::tobase()->get();
        $settings = CmsServices::pluck('value', 'key');
        return $this->sendResponse(['faqLists' => $faqLists, 'settings' => $settings], 'About us retrieved successfully');
    }
}
