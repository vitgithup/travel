<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'roles'=>$user->getRoleNames(),
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        $user = User::where('email',$credentials['email'])->where('status',1)->first();
        if(! $user ){
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }
        $user = Auth::user();
        $user->status;
        $token = $user->createToken('main')->plainTextToken;
        // $user->with('roles');
        return response([
            'user' => self::addRoles2User($request),
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }
    private static function addRoles2User(Request $request)
    {
        $user = $request->user();
        // $user = Auth::user();
        $jsonUser = json_encode($user);
        $objUser = json_decode($jsonUser,1);

        $jsonRoles = json_encode($user->getRoleNames());
        $objRoles = json_decode($jsonRoles,1);
        $objUser['roles'] = $objRoles;
        return $objUser;
    }
    public function me(Request $request)
    {
        // return $request->user();

        return response(self::addRoles2User($request));

    }
}
