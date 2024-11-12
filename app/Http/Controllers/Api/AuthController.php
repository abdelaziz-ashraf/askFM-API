<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request) {

        if($request->hasFile('image')) {
            $imageName = time() . '-' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $request->image = $imageName;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $request->image
        ]);

        $token = $user->createToken('Auth Token')->plainTextToken ;

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
