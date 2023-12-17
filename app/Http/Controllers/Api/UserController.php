<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $user;
    public function __construct(UserService $userService)
    {
        $this->user = $userService;
    }
    /**
     * registration 
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->user->registration($data);
        return response()->json(['status' => 'success', 'massage' => 'Registration successfully'], Response::HTTP_CREATED);
    }

    /**
     * user login
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'These credentials do not match our record!'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $data['token'] = $user->createToken('auth_token')->plainTextToken;

        return response($data);
    }

    /**
     * user logout
     */
    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['status' => 'success', 'massage' => 'User Logout Successfully'], 200);
    }

    /**
     * user profile
     */
    public function profile(int $id): JsonResponse
    {
        $user = $this->user->profile($id);
        $data = new UserResource($user);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
