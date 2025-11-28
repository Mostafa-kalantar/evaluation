<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Traits\AuthHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    use AuthHelper;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="user2"),
     *             @OA\Property(property="password", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="object",
     *                     @OA\Property(property="authorization", type="string", example="9|tcntd..."),
     *                     @OA\Property(property="exp", type="integer", example=1764936235)
     *                 ),
     *                 @OA\Property(property="redirect_route", type="string", example="http://localhost/evaluation/public/dashboard")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="User not found")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="The username field is required.")
     *             )
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(['username' => $request->username, 'password' => $request->password])) {
            $token = $this->createBearer(auth()->user());
            return apiResponse(['token' => $token, 'redirect_route' => route('dashboard.index')]);
        }
        return apiResponse(null, null, 'User not found');
    }

    /**
     * @OA\Get(
     *     path="/api/auth/logout",
     *     summary="Logout user",
     *     description="Invalidate the current access token and logout the user.",
     *     tags={"Authentication"},
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer access token",
     *         @OA\Schema(type="string", example="Bearer {token}")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Logged out successfully"),
     *             @OA\Property(property="redirect_route", type="string", example="http://localhost/auth")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing token",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        $this->removeUserAccessToken($token);
        $this->removeWebGuard($request);
        return redirect()->route('login');
    }

}
