<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"Authentication"},
     *     summary="Login the user - AINDA FORA DE FUNCIONAMENTO",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *             @OA\Property(property="password", type="string", example="senha123")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK", @OA\JsonContent(
     *         @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *     )),
     *     @OA\Response(response="422", description="Unprocessable Entity")
     * )
     *
     * @param AuthRequest $req
     * @return array|JsonResponse
     */

    public function login(AuthRequest $req): Array | JsonResponse
    {
        $data = $req->all();
        $user = User::where('email', $data['email'])->first();
        if(password_verify($data['password'], $user->getAuthPassword())){
            return [
                'token' => $user->createToken(time())->plainTextToken
            ];
        }
        else return response()->json(['error'=>"Data doesn't match"], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     tags={"Authentication"},
     *     summary="Logout the user, deleting the token  - AINDA FORA DE FUNCIONAMENTO",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     *
     * @return void
    */
    public function logout(): void
    {
        auth()->user()->currentAccessToken()->delete();
    }
}