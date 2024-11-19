<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Actions\UplaodFileAction;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, UplaodFileAction $uplaoder)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $request->hasFile('image') ? $uplaoder->handle($request->image) : null
        ]);

        event(new Registered($user));

        $token = $user->createToken('verification')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => UserResource::make($user)
        ], 'User Registered Successfully', 200);
    }

    public function login(LoginRequest $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->email_verified_at == null) {
                throw ValidationException::withMessages([
                    'email' => ['please verify ur email.'],
                ]);
            }

            $token = $user->createToken('Auth Token')->plainTextToken;

            return $this->successResponse([
                'token' => $token,
                'user' => UserResource::make($user)
            ], 'User Login Successfully', 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)->first();
        if (!$verificationCode) {
            return $this->errorResponse('Invalid code', 401);
        }

        if ($verificationCode->expires_at < now()) {
            return $this->errorResponse('Expired code', 401);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
        $verificationCode->delete();

        return $this->successResponse(null, 'Code Verified Successfully', 200);
    }
}
