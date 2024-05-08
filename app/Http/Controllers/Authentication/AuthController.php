<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Authentication\AuthService;

final class AuthController extends Controller
{
    use ApiResponses;
    private AuthService $authService;
    private User $user;

    public function __construct(AuthService $authService, User $user)
    {
        $this->authService = $authService;
        $this->user = $user;
    }

    public function registerHandler(RegistrationRequest $request)
    {
        //validated request
        $validatedData = $request->validated();
        $result = $this->authService->register($validatedData);
        return $result;
    }

    public function verifyEmailHandler($token)
    {
        $result = $this->authService->verifyEmail($token);
        return $result;
    }

    public function loginHandler(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($credentials);

            return $result;
        } catch (\Exception $e) {
            Log::error('An error occur upon login: ', [$e->getMessage()]);
            return $this->invalidRequestFields(['error' => $e->getMessage()], 401);
        }
    }

    public function forgotPasswordHandler(Request $request)
    {
        try {
            $validatedBody = $request->validate([
                'email' => 'required|email'
            ]);

            $result = $this->authService->forgotPassword($validatedBody);
            return $result;
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage(), 500);
        }
    }

    public function resetPasswordHandler(Request $request, $token)
    {
        try {
            $validatedBody = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#€.\\-])[A-Za-z\d@$!%*?&#€.\\-]+$/',
            ]);

            $result = $this->authService->resetPassword($validatedBody, $token);
            return $result;
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage(), 500);
        }
    }

    public function logoutHandler()
    {
        $authUser = auth()->user();

        $result = $this->authService->logout($authUser);

        return $result;
    }
}
