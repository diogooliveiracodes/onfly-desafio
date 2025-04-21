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
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request): Response
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {

            return response([
                'message' => 'The provided credentials are incorrect.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return response([
            'token' => $user->createToken($request->input('device_name'))->plainTextToken,
        ], ResponseAlias::HTTP_OK);
    }
}
