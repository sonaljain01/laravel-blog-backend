<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Str;
use Http;

class AuthController extends Controller
{

    //Register API (POST, Formdata)
    public function register(RegisterRequest $request)
    {
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "type" => $request->type,
        ];
        $register = User::create($data);

        // Response
        if ($register) {
            $this->sendEmail($register);
            return response()->json([
                "status" => true,
                "message" => "User registered successfully",
                'data' => $register
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'User not created',
        ]);
    }

    //Login API (POST, Formdata)
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        }

        if ($user->email_verified_at == null) {
            return response()->json([
                "status" => false,
                "message" => "Email not verified"
            ]);
        }

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!empty($token)) {

            return response()->json([
                "status" => true,
                "message" => "User logged in succcessfully",
                "token" => $token
            ]);
        }

        // Response
        return response()->json([
            "status" => false,
            "message" => "Invalid details"
        ]);
    }

    //Profile API (GET, autherization token value JWT)
    public function profile()
    {
        $userdata = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata,
        ]);
    }

    public function verifyEmail(string $userId, string $token)
    {
        $user = User::where('id', $userId)->first();
        if ($user->remember_token != $token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token',
            ], 500);
        }
        if ($user) {
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified',
                ], 500);
            } else {
                $user->update([
                    'email_verified_at' => now(),
                    "remember_token" => null
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully',
                ], 200);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ], 500);
    }

    protected function sendEmail(object $user)
    {
        $app_url = env("APP_URL");
        $token = Str::random(20);
        $url = "$app_url/api/verify/email/$user->id/$token";
        $data = [
            "email" => $user->email,
            "body" => "<a>$url</a>"
        ];
        $user->update([
            'remember_token' => $token
        ]);
        Http::post("https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjYwNTZkMDYzZTA0M2M1MjZhNTUzMTUxM2Ei_pc", $data);
    }

    //Logout API(GET)
    public function logout()
    {
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if ($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }
}
