<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends BaseController
{


    public function RegistrationAction(RegistrationRequest $request)
    {

        $user  = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $success['token'] = $user->createToken('UrlShortenerApp')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User created successfully');
    }


    public function LoginAction(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('UrlShortenerApp')->accessToken;
            $success['name'] = $user->name;

            return $this->sendError($success, 'User login successfully');
        } else {
            return $this->sendError('UnAuthenticated', ['error' => 'Unauthorized']);
        }
    }
}
