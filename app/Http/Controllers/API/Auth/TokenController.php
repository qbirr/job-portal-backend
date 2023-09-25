<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
class TokenController extends Controller {

    public function createToken(Request $request) {
        $token = $request->user()->createToken($request->token_name);

        return ['access_token' => $token->plainTextToken];
    }

    /**
     * Login user
     * @param Request $request
     * @return array
     */
    #[Operation]
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return [
            'access_token' => $token,
            'user' => $user,
            'roles' => $user->roles->pluck('name'),
        ];
    }
}
