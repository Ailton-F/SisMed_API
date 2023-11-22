<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users/",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get all users",
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     )
     * )
     *
     * @return User
     */
    public function list(): User
    {
        return User::all();
    }


    /**
     * @OA\Get(
     *     path="/api/users/",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get paginated users",
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     ),
     *     @OA\Response(
     *          response="403",
     *          description="Unauthorized"
     *      )
     * )
     *
     * @return LengthAwarePaginator
     */
    public function paginated(): LengthAwarePaginator
    {
        return User::all()->paginate(10, ['*'], 'page');
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", example="2"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     ),
     *     @OA\Response(response="404", description="User not found")
     * )
     *
     * @param int $id
     * @return Builder|array|Collection|Model|JsonResponse
     */

    public function listById(int $id): Builder|array|Collection|Model|JsonResponse
    {
        $user = auth()->user();
        if (!$user->admin or $user->id != $id) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403);
        }

        return User::findOrFail($id);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="clinic_id", type="integer", example="1"),
     *             @OA\Property(property="cpf", type="string", example="11111111111"),
     *             @OA\Property(property="username", type="string", example="teste"),
     *             @OA\Property(property="email", type="string", example="teste@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345"),
     *             @OA\Property(property="type", type="string", example="P")
     *         )
     *     ),
     *     @OA\Response(response="201", description="User created successfully"),
     *     @OA\Response(response="422", description="Unprocessable Entity")
     * )
     *
     * @param UserRequest $req
     * @return User
     */
    public function add(UserRequest $req): User
    {
        $data = $req->all();
        $data['password'] = bcrypt($req->password);

        return User::create($data);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update user by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer", example="2")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="clinic_id", type="integer", example="1"),
     *             @OA\Property(property="cpf", type="string", example="11111111111"),
     *             @OA\Property(property="username", type="string", example="teste2"),
     *             @OA\Property(property="email", type="string", example="teste2@gmail.com"),
     *             @OA\Property(property="admin", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(response="200", description="User updated successfully"),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity")
     * )
     *
     * @param Request $req
     * @param int $id
     * @return jsonResponse
     */

    public function edit(Request $req, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user->admin and $user->id != $id) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403);
        }

        $user = User::findOrFail($id);
        $data = $req->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($req->password);
        }
        $user->update($data);
        return $user;
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer", example="2")
     *     ),
     *     @OA\Response(response="204", description="User deleted successfully"),
     *     @OA\Response(response="404", description="User not found")
     * )
     *
     * @param int $id
     * @return jsonResponse
     */


    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user->admin and $user->id != $id) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403);
        }

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([], 204);
    }
}