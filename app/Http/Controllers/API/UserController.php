<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class UserController extends Controller {
    public function profile() {
        return auth()->user();
    }
}
