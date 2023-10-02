<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ThemeController extends Controller {
    public function save() {
        return response()->json(['success' => true])->cookie('theme', request('theme', null), 365 * 24 * 60 * 60);
    }
}
