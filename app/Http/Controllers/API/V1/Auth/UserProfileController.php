<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserProfileController extends Controller
{
    public function show(): Authenticatable
    {
        return Auth::user();
    }

    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        $user = User::find($user->id);

        $validatedData = $request->validate([
            'name' => ['required','unique:users,name,'.$user->id],
            'email' => ['required', 'unique:users,email,'.$user->id],
        ]);
        


        $user->update($validatedData);

        $user->save();

        return response()->json([
            'user_id' => $user->id,
        ], ResponseAlias::HTTP_CREATED);

    }
}
