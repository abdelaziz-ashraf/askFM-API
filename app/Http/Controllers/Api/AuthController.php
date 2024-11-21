<?php

namespace App\Http\Controllers\Api;

use App\Actions\UploadFileAction ;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\EmailVerifiedSuccessfullyNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, UploadFileAction $uploader)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['image'] = $request->hasFile('image') ? $uploader->handle($request->image) : null;
        $user = User::create($data);

        event(new Registered($user));

        return SuccessResponse::send('Registered successfully.', [
            'token' => $user->createToken('verification')->plainTextToken,
            'user' => new UserResource($user),
        ]);
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

            return SuccessResponse::send('Login successfully.', [
                'token' => $user->createToken('Auth Token')->plainTextToken,
                'user' => new UserResource($user),
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            throw ValidationException::withMessages([
               'error' => ['The provided credentials are incorrect.'],
            ]);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)->first();
        if (! $verificationCode) {
            throw ValidationException::withMessages([
                'error' => ['Invalid code.'],
            ]);
        }

        if ($verificationCode->expires_at < now()) {
            throw ValidationException::withMessages([
                'error' => ['Expired code.'],
            ]);
        }

        $user->markEmailAsVerified();
        $user->notify(new EmailVerifiedSuccessfullyNotification());
        $verificationCode->delete();

        return SuccessResponse::send('Code verified successfully.');
    }
}
