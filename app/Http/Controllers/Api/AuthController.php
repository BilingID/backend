<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ResetEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Constants\HttpResponseCode as ResponseCode;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registeredUser = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            // 'role' => config('user.roles.klien')
            'role' => "klien"
        ]);

        if ($registeredUser)
        {
            $token = $registeredUser->createToken('auth_token')->plainTextToken;
            return $this->success(['token' => $token], "User registered successfully.");
        }

        return $this->error([], "Unable to register user.");

    }

    public function login(AuthRequest $request)
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return $this->success(['token' => $token], "User login successfully.");
        }

        return $this->error([], "Your email or password is incorrect.", ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $tokenDeleted = Auth::user()->currentAccessToken()->delete();
        
        if ($tokenDeleted) {
            return $this->success([], "User logout successfully.");
        }
        return $this->error([], "Unable to logout.");        
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        
        if ($user->save())
            return $this->success([], "Password updated successfully.");

        return $this->error([], "Unable to update password.");
    }

    public function updateEmail(ResetEmailRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password))
            return $this->error([], "Your password is incorrect.", ResponseCode::HTTP_UNAUTHORIZED);

        $user->email = $request->email;
        
        if (!$user->save())
            return $this->error([], "Unable to update email.");

        return $this->success([], "Email updated successfully.");
    }
}
