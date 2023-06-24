<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class UserRegisterController extends Controller
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','unique:users'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails())
        {
            //return error
            $response['result'] = false;
            $response['message'] = $validator->messages();

            return response()->json($response,411);
        }

        $input = $request->all();

        $input['password'] = bcrypt($request->password);

        $user = User::create($input);

        event(new Registered($user));

        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json($success, ResponseAlias::HTTP_CREATED);
    }
}
