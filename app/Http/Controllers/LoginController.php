<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth', ['except' => ['login','register']]);
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                                 $validator->validated(),
                                 ['password' => bcrypt($request->password)]
                             ));

        return response()->json([
                                    'message' => 'User successfully registered',
                                    'user' => $user
                                ], 201);
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
                                    'access_token' => $token,
                                    'token_type' => 'bearer',
                                ]);
    }

    public function userInfor(Request $request){

        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
}
