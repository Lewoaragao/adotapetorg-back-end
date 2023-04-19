<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    /**
     * @var user
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function loginUser(Request $request): Response
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // if ($validator->fails()) {

        //     return Response(['message' => $validator->errors()], 401);
        // }

        // if (Auth::attempt($request->all())) {

        //     $user = Auth::user();

        //     $success =  $user->createToken('MyApp')->plainTextToken;

        //     return Response(['token' => $success], 200);
        // }

        return Response(['message' => 'email or password wrong'], 401);
    }

    public function userDetails(): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user], 200);
        }

        return Response(['data' => 'Unauthorized'], 401);
    }

    public function logout(): Response
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return Response(['data' => 'User Logout successfully.'], 200);
    }
}
