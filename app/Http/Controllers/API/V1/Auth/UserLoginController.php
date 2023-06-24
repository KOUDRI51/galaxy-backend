<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            // Return validation errors
            $response = [
                'result' => false,
                'message' => $validator->messages(),
            ];

            return response()->json($response, 411);
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $user = Auth::user();

            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token]);
        } else {
            throw ValidationException::withMessages([
                'error' => ['Provided credentials are incorrect.'],
            ]);
        }
    }
}
