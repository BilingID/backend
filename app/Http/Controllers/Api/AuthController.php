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
use App\Http\Requests\GoogleAuthRequest;
use App\Constants\HttpResponseCode as ResponseCode;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registeredUser = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($registeredUser)
        {
            $token = $registeredUser->createToken('auth_token')->plainTextToken;
            return $this->success(['token' => $token], "User registered successfully");
        }

        return $this->error([], "Unable to register user");

    }

    public function registerWithGoogle(GoogleAuthRequest $request)
    {
        $googleAccessToken = $request->access_token;
        
        $response = Http::get('https://www.googleapis.com/oauth2/v1/userinfo', [
            'access_token' => $googleAccessToken,
        ]);

        if ($response->failed())
            return $this->error([], "Unable to login with Google");
        
        $googleEmail = $response->json("email");

        $user = User::where('email', $googleEmail)->first();
        if ($user)
            return $this->error([], "This email is already registered", ResponseCode::HTTP_UNAUTHORIZED);

        $registeredUser = User::create([
            'fullname' => $response->json("name"),
            'email' => $googleEmail,
            'profile_photo' => $response->json("picture"),
        ]);

        $token = $registeredUser->createToken('auth_token')->plainTextToken;
        return $this->success(['token' => $token, 'resp' => $response->json()], "User registered successfully");
    }


    public function login(AuthRequest $request)
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return $this->success(['token' => $token], "User login successfully");
        }

        return $this->error([], "Your email or password is incorrect", ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function loginWithGoogle(GoogleAuthRequest $request)
    {
        $googleAccessToken = $request->access_token;
        
        $response = Http::get('https://www.googleapis.com/oauth2/v1/userinfo', [
            'access_token' => $googleAccessToken,
        ]);

        if ($response->failed())
            return $this->error([], "Unable to login with Google");
        
        $googleEmail = $response->json("email");

        $user = User::where('email', $googleEmail)->first();

        if (!$user)
            return $this->error([], "Your email is not registered", ResponseCode::HTTP_UNAUTHORIZED);

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(['token' => $token], "User login successfully");
    }

    public function logout()
    {
        $tokenDeleted = Auth::user()->currentAccessToken()->delete();
        
        if ($tokenDeleted) {
            return $this->success([], "User logout successfully");
        }
        return $this->error([], "Unable to logout");        
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        
        if ($user->save())
            return $this->success([], "Password updated successfully");

        return $this->error([], "Unable to update password");
    }

    public function updateEmail(ResetEmailRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password))
            return $this->error([], "Your password is incorrect", ResponseCode::HTTP_UNAUTHORIZED);

        $user->email = $request->email;
        
        if (!$user->save())
            return $this->error([], "Unable to update email");

        return $this->success([], "Email updated successfully");
    }
}
