<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Service\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserControler extends ApiController
{
    public function register(RegisterRequest $request, UserService $service)
    {
        DB::beginTransaction();
        try {
            $user = $service->create($request);
            $token = $service->createToken($user);
            $service->sendMail($user);
            $smsStatus = $service->sendSMS($user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        $resp = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->successResponse($resp, 'User register successfully. ');
    }

    public function login(LoginRequest $request, UserService $service)
    {
        $auth = $service->authCheck($request->email, $request->password);

        if ($auth) {
            $resp = [
                'user' => $service->getAuthUser(),
                'token' => $service->createToken($service->getAuthUser())
            ];
            return $this->successResponse($resp, 'User login successfully.');
        }
        return $this->errorResponse([], 'email or password mismatch', 401);
    }

    public function profile(UserService $service)
    {
        return $this->successResponse($service->getAuthUser(), 'ok');
    }

    public function profileUpdate(UserProfileUpdateRequest $request, UserService $service)
    {
        return $this->successResponse($service->update($service->getAuthUser()->id, $request), 'ok');
    }

    public function chengePassword(UserChangePasswordRequest $request, UserService $service)
    {
        $changePassword = $service->changePasswrd($service->getAuthUser()->id, $request);
        if ($changePassword) {
            return $this->successResponse($changePassword, 'ok');
        }
        return $this->errorResponse($changePassword, 'old password mismatch');
    }
}
