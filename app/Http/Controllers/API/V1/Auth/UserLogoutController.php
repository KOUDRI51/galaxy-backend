<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserLogoutController extends Controller
{
    public function __invoke()
    {
        // Get the authenticated user's token
        $user = Auth::user()->token();

        // Revoke the token (log out the user)
        $user->revoke();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'User logged out successfully',
        ], ResponseAlias::HTTP_OK);
    }
}
