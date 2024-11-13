<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Events\UserRegistered;
use App\Actions\UplaodFileAction;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, UplaodFileAction $uplaoder) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $request->hasFile('image') ? $uplaoder->handle($request->image) : null
        ]);

        //event(new UserRegistered($user));

        $token = $user->createToken($user->name)->plainTextToken ;

        return $this->successResponse([
            'token' => $token,
            'user' => $user
        ], 'User Registered Successfully', 200);
    }

    public function login(LoginRequest $request) {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('Auth Token')->plainTextToken;

            return $this->successResponse([
                'token' => $token,
                'user' => $user
            ], 'User Login Successfully', 200);

        }

        return $this->errorResponse('Invalid credentials', 401);

    }
}
