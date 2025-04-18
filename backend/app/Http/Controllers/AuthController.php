<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Summary of login
     * @param \App\Http\Requests\LoginRequest $request
     * @return string
     */
    public function login(LoginRequest $request): Response
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {

            return response([
                'message' => 'The provided credentials are incorrect.',
            ],  ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return response([
            'token' => $user->createToken($request->device_name)->plainTextToken,
        ],  ResponseAlias::HTTP_OK);
    }
}
