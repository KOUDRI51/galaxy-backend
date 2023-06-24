<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserPasswordController extends Controller
{
    public function changePassword(Request $request) : JsonResponse
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:8', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // Get the authenticated user
        $user = Auth::user();
        $user = User::find($user->id);

        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        // Update the user's password
        $user->password = Hash::make($request->input('password'));

        // Revoke the user's tokens (logout the user from all devices)
        $user->tokens()->delete();

        // Generate a new token for the user
        $token = $user->createToken('MyApp')->accessToken;

        // Save the updated user
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
            'access_token' => $token,
        ], ResponseAlias::HTTP_OK);
    }
}
