<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
class AuthController extends Controller
{

   /**
 *@OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="budget", type="number", format="double", nullable=true),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Register a new user with the provided credentials.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User registration data",
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User registered successfully"),
 *             @OA\Property(property="user", ref="#/components/schemas/User"),
 *         ),
 *     ),
 * )
 */
    
 
    public function register(UserRegistration $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user],201);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Log in user",
     *     description="Authenticate a user with the provided credentials and generate an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login data",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="access_token"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *         ),
     *     ),
     * )
     */
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json(['user' => $user, 'token' => $token], 200);
    }


/**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="Log out user",
     *     description="Revoke the current user's access token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully"),
     *         ),
     *     ),
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
