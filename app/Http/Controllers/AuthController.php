<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ResetEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Constants\AuthConstants;
use App\Constants\HttpResponseCode as ResponseCode;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registeredUser = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => config('user.roles.klien')
        ]);

        if ($registeredUser)
        {
            $token = $registeredUser->createToken('auth_token')->plainTextToken;
            return $this->success(['token' => $token], AuthConstants::REGISTER);
        }

        return $this->error([], AuthConstants::REGISTER_ERROR);

    }

    public function login(AuthRequest $request)
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return $this->success(['token' => $token], AuthConstants::LOGIN);
        }

        return $this->error([], AuthConstants::VALIDATION, ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $tokenDeleted = Auth::user()->currentAccessToken()->delete();
        
        if ($tokenDeleted) {
            return $this->success([], AuthConstants::LOGOUT);
        }
        return $this->error([], AuthConstants::LOGOUT_ERROR);        
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        
        if ($user->save())
            return $this->success([], AuthConstants::PASSWORD_UPDATED);

        return $this->error([], AuthConstants::PASSWORD_UPDATED_ERROR);
    }

    public function updateEmail(ResetEmailRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password))
            return $this->error([], AuthConstants::INCORRECT_PASSWORD, ResponseCode::HTTP_UNAUTHORIZED);

        $user->email = $request->email;
        
        if (!$user->save())
            return $this->error([], AuthConstants::EMAIL_UPDATED_ERROR);

        return $this->success([], AuthConstants::EMAIL_UPDATED);
    }
}
