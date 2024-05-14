<?php

namespace App\Services\Authentication;

use Exception;
use App\Models\User;
use App\Enums\UserStatus;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use App\Services\OtpService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Mail\PasswordResetLink;
use App\Jobs\SendEmailVerification;
use App\Jobs\SendPasswordResetLink;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\RegistrationResource;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    use ApiResponses;
    private User $userModel;
    private OtpService $otpService;

    public function __construct(User $userModel, OtpService $otpService)
    {
        $this->userModel = $userModel;
        $this->otpService = $otpService;
    }

    public function register($request)
    {
        try {
            //generate token
            $otpToken = $this->otpService->generateToken();

            //create user
            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'otp_token' => $otpToken,
                'password' => $request->password,

            ]);

            // Dispatch job to send email for email verification
            SendEmailVerification::dispatch($user);

            $userData = new AuthResource($user);

            return $this->successApiResponse('User successfully registered', $userData, 200);
        } catch (\Exception $e) {
            Log::error('Error while registering user: ', [$e->getMessage()]);
            return $this->errorApiResponse('An error has occur while creating user. please contact the administrator', 500);
        }
    }

    public function verifyEmail($token)
    {
        try {
            $user = User::where('otp_token', $token)->first();

            if (!$user) {
                return $this->notFoundApiResponse(['error' => 'Invalid Token'], 404);
            }
            //verify email from the database
            $user->email_verified_at = now();
            $user->active = true;
            $user->otp_token = null;
            $user->save();

            return $this->successNoDataApiResponse('Email verified successfully', 200);
        } catch (\Exception  $e) {
            Log::error('Error while verifying user email: ', [$e->getMessage()]);
            return $this->errorApiResponse('An Error occur while verifying user email. please contact the administrator', 500);
        }
    }

    public function login($credentials)
    {
        try {
            // Attempt to retrieve the user by email
            $user = User::where('email', $credentials['email'])->first();

            // If user doesn't exist or password doesn't match, throw validation exception
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return $this->notFoundApiResponse('Invalid credentials');
            }

            if (!isset($user->email_verified_at)) {
                return $this->unauthorizedApiResponse('User is yet to verify email');
            }
            $userData = new AuthResource($user);
            // Log in the user using Sanctum's login method
            $token = $user->createToken('Login Token')->plainTextToken;

            return $this->successApiResponse('Login successfully', ['token' => $token, 'user' => $userData,], 200);
        } catch (\Exception $e) {
            Log::error('An error occur while login in: ', [$e->getMessage()]);
            return $this->errorApiResponse('An error occur while trying to log in. Please contact the administrator', 500);
        }
    }
    public function forgotPassword($validatedBody)
    {
        try {
            $user = User::where('email', $validatedBody['email'])->first();

            if (!$user) {
                return $this->notFoundApiResponse('User with the provided email not found');
            }
            $token = Str::random(60);

            $user->update(['otp_token' => $token]);

            // Dispatch job to send email for password reset link
            SendPasswordResetLink::dispatch($user);

            return $this->successNoDataApiResponse('Password reset request as been sent to your email', 200);
        } catch (\Exception $e) {
            Log::error('An error occur while making forgot password request: ', [$e->getMessage()]);

            return $this->errorApiResponse('An error occur while making forgot password request. Please contact the administrator', 500);
        }
    }

    public function resetPassword($validatedBody, $token)
    {
        try {
            $user = User::where('email', $validatedBody['email'])
                ->where('otp_token', $token)
                ->first();

            switch ($user) {
                case null:
                    return $this->notFoundApiResponse('User with the provided token not found');

                default:
                    $user->update([
                        'password' => Hash::make($validatedBody['password']),
                        'otp_token' => null,
                    ]);

                    return $this->successNoDataApiResponse('Password reset successfully', 200);
            }
        } catch (\Exception $e) {
            Log::error('An error occur while making reset password request: ', [$e->getMessage()]);

            return $this->errorApiResponse('An error occur while resetting password. Please contact the administrator', 500);
        }
    }

    public function logout($authUser)
    {
        try {
            $authUser->tokens()->delete();
            return $this->successNoDataApiResponse('Logout successfully');
        } catch (\Exception $e) {
            Log::error('An error occur while login out: ', [$e->getMessage()]);

            return $this->errorApiResponse('An error occur while login out. Please contact the administrator', 500);
        }
    }
}
