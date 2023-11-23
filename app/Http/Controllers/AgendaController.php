<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Http\Requests\AgendaRequest;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agendas/",
     *     tags={"Agenda"},
     *     security={{"bearerAuth": {}}},
     *     summary="Listar agendas",
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     )
     * )
     *
     * @return Agenda
     */
    public function list(): Agenda
    {
        return Agenda::all();
    }


    /**
     * @OA\Get(
     *     path="/api/agendas/paginated",
     *     tags={"Agenda"},
     *     security={{"bearerAuth": {}}},
     *     summary="Paginação de Agenda",
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     )
     * )
     *
     * @return LengthAwarePaginator
     */
    
    public function paginated(): LengthAwarePaginator
    {
        return Agenda::all()->paginate(10, ['*'], 'page');
    }

    /**
     * @OA\Get(
     *     path="/api/agendas/{id}",
     *     tags={"Agenda"},
     *     summary="Procurar por agenda específica",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Agenda ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", example="2"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     ),
     *     @OA\Response(response="404", description="Agenda not found")
     * )
     *
     * @param int $id
     * @return Builder|array|Collection|Model|JsonResponse
     */

    public function listById(int $id): Builder|array|Collection|Model|JsonResponse
    {
        return Agenda::findOrFail($id);
    }

    /**
     * @OA\Post(
     *     path="/api/agendas",
     *     tags={"Agenda"},
     *     summary="Cria uma nova agenda",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="observacao", type="string", example="Observação a respeito da data cadastrada"),
     *             @OA\Property(property="dt_hr", type="string", example="2000-01-01 00:00:00")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Agenda created successfully"),
     *     @OA\Response(response="422", description="Unprocessable Entity")
     * )
     *
     * @param AgendaRequest $req
     * @return Agenda
     */
    public function add(AgendaRequest $req): Agenda
    {
        $data = $req->all();
        return Agenda::create($data);
    }

    /**
     * @OA\Put(
     *     path="/api/agendas/{id}",
     *     tags={"Agenda"},
     *     summary="Update Agenda by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Agenda ID",
     *         required=true,
     *         @OA\Schema(type="integer", example="2")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="observacao", type="string", example="Observação a respeito da data cadastrada"),
     *             @OA\Property(property="dt_hr", type="string", example="2022-05-08 14:30:45")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Agenda updated successfully"),
     *     @OA\Response(response="404", description="Agenda not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity")
     * )
     *
     * @param Request $req
     * @param int $id
     * @return jsonResponse
     */

    public function edit(Request $req, int $id): JsonResponse
    {
        $agenda = Agenda::findOrFail($id);
        $data = $req->all();

        $agenda->update($data);
        return $agenda;
    }

    /**
     * @OA\Delete(
     *     path="/api/agendas/{id}",
     *     tags={"Agenda"},
     *     security={{"bearerAuth": {}}},
     *     summary="Deleta Agenda",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Agenda ID",
     *         required=true,
     *         @OA\Schema(type="integer", example="2")
     *     ),
     *     @OA\Response(response="204", description="Agenda deleted successfully"),
     *     @OA\Response(response="404", description="Agenda not found")
     * )
     *
     * @param int $id
     * @return jsonResponse
     */


    public function destroy(int $id): JsonResponse
    {
        $agenda = User::findOrFail($id);
        $agenda->delete();
        return response()->json([], 204);
    }
}
