<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Store a newly created user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        try {
            User::create(['name'=>$request->name, 'email'=>$request->email, 'password'=>$request->password]);
            return response()->json(
                [
                    "status"=> "success",
                    "message"=> "User Created Succesfully..."
                ], 200
                );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "status"=> "error",
                    "message"=> "Error user signup"
                ], 400
                );
            }
    }

    /**
     *  Login for auth token 
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            //code...
            $user = User::where('email', $request->email)->first();
            
            if(! $user || !Hash::check($request->password, $user->password)){
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect']
                ]);
            }
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json(['message' => "Login Successs", 'token'=>['value'=>$token, 'type'=>'Bearer']], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => "error occured during login"], 400);
        }
    }
}
