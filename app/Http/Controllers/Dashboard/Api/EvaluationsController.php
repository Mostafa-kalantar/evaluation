<?php

namespace App\Http\Controllers\Dashboard\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvaluationsController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/evaluations",
     *     summary="Get evaluations list",
     *     description="Returns paginated list of evaluations. Requires Authorization token.",
     *     tags={"Evaluations"},
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer Token",
     *         @OA\Schema(type="string", example="Bearer {token}")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated evaluations list",
     *         @OA\JsonContent(
     *             @OA\Property(property="component", type="string", example="Dashboard/Evaluations/Index"),
     *
     *             @OA\Property(property="props", type="object",
     *                 @OA\Property(property="evaluations", type="object",
     *
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *
     *                     @OA\Property(property="data", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=3),
     *                             @OA\Property(property="title", type="string", example="user 2 scope updated"),
     *                             @OA\Property(property="created_at", type="string", example="2025-11-28 11:19:57"),
     *                             @OA\Property(property="updated_at", type="string", example="2025-11-28 11:28:04"),
     *
     *                             @OA\Property(property="user", type="object",
     *                                 @OA\Property(property="id", type="integer", example=3),
     *                                 @OA\Property(property="username", type="string", example="user2"),
     *                                 @OA\Property(property="scope_title", type="string", example="US branch")
     *                             )
     *                         )
     *                     ),
     *
     *                     @OA\Property(property="first_page_url", type="string", example="http://localhost/evaluation/public/api/evaluations?page=1"),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=1),
     *                     @OA\Property(property="last_page_url", type="string", example="http://localhost/evaluation/public/api/evaluations?page=1"),
     *
     *                     @OA\Property(property="links", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="url", type="string", nullable=true),
     *                             @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                             @OA\Property(property="page", type="integer", nullable=true),
     *                             @OA\Property(property="active", type="boolean", example=false)
     *                         )
     *                     ),
     *
     *                     @OA\Property(property="next_page_url", type="string", nullable=true),
     *                     @OA\Property(property="path", type="string", example="http://localhost/evaluation/public/api/evaluations"),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="prev_page_url", type="string", nullable=true),
     *                     @OA\Property(property="to", type="integer", example=1),
     *                     @OA\Property(property="total", type="integer", example=1)
     *                 )
     *             ),
     *
     *             @OA\Property(property="url", type="string", example="/evaluation/public/api/evaluations"),
     *             @OA\Property(property="version", type="string", example=""),
     *             @OA\Property(property="clearHistory", type="boolean", example=false),
     *             @OA\Property(property="encryptHistory", type="boolean", example=false)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid token",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     )
     * )
     */

    public function list()
    {
        $evaluations = Evaluation::query()->owner()->scope()->with('user')
            ->latest()
            ->paginate(10)
            ->through(fn($e) => [
                'id' => $e->id,
                'title' => $e->title,
                'created_at' => !empty($e->created_at) ? $e->created_at->format('Y-m-d H:i:s') : '',
                'updated_at' => !empty($e->updated_at) ? $e->updated_at->format('Y-m-d H:i:s') : '',
                'user' => [
                    'id' => $e->user->id,
                    'username' => $e->user->username,
                    'scope_title' => $e->user->scope->title ?? '',
                ]
            ]);

        return Inertia::render('Dashboard/Evaluations/Index', [
            'evaluations' => $evaluations,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/evaluations/{id}",
     *     summary="Get evaluation for update",
     *     description="Returns a single evaluation with user info for editing. Requires Authorization token.",
     *     tags={"Evaluations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Evaluation ID",
     *         @OA\Schema(type="integer", example=4)
     *     ),
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
     *         description="Evaluation retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="component", type="string", example="Dashboard/Evaluations/Update"),
     *
     *             @OA\Property(property="props", type="object",
     *                 @OA\Property(property="errors", type="object", example={}),
     *                 @OA\Property(property="evaluation", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="scope_id", type="integer", example=2),
     *                     @OA\Property(property="title", type="string", example="123"),
     *                     @OA\Property(property="description", type="string", nullable=true, example=null),
     *                     @OA\Property(property="created_at", type="string", example="2025-11-28T12:28:13.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", example="2025-11-28T12:28:13.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="scope_id", type="integer", example=2),
     *                         @OA\Property(property="first_name", type="string", nullable=true, example=null),
     *                         @OA\Property(property="last_name", type="string", nullable=true, example=null),
     *                         @OA\Property(property="username", type="string", example="user2"),
     *                         @OA\Property(property="email", type="string", nullable=true, example=null),
     *                         @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="created_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="updated_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                     )
     *                 )
     *             ),
     *
     *             @OA\Property(property="url", type="string", example="/evaluation/public/dashboard/evaluations/update/4"),
     *             @OA\Property(property="version", type="string", example=""),
     *             @OA\Property(property="clearHistory", type="boolean", example=false),
     *             @OA\Property(property="encryptHistory", type="boolean", example=false)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Evaluation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Evaluation not found"),
     *             @OA\Property(property="errors", type="array", @OA\Items())
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $evaluation = Evaluation::with('user')->findOrFail($id);
        return Inertia::render('Dashboard/Evaluations/Update',
            [
                'evaluation' => $evaluation,
            ]);
    }

    /**
     * @OA\Post(
     *     path="/api/evaluations/store",
     *     summary="Create new evaluation",
     *     description="Creates a new evaluation. Authorization token is required.",
     *     tags={"Evaluations"},
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(type="string", example="Bearer {token}")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="test")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Evaluation created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="messages.success_response"),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="array",
     *                 @OA\Items(example="The title field is required.")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Evaluation::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'scope_id' => auth()->user()->scope_id,
        ]);

        return apiResponse();
    }

    /**
     * @OA\Put(
     *     path="/api/evaluations/{id}",
     *     summary="Update an evaluation",
     *     description="Updates an existing evaluation. Authorization token is required.",
     *     tags={"Evaluations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Evaluation ID to update",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(type="string", example="Bearer {token}")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="test changed")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Evaluation updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="messages.success_response"),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="array",
     *                 @OA\Items(example="The title field is required.")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Evaluation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Evaluation not found"),
     *             @OA\Property(property="errors", type="array", @OA\Items())
     *         )
     *     )
     * )
     */

    public function update($id, Request $request)
    {
        $evaluation = Evaluation::query()->findOrFail($id);
        $evaluation->update(['title' => $request->title]);
        return apiResponse();

    }

    /**
     * @OA\Delete(
     *     path="/api/evaluations/{id}",
     *     summary="Delete an evaluation",
     *     description="Deletes an existing evaluation. Authorization token is required.",
     *     tags={"Evaluations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Evaluation ID to delete",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(type="string", example="Bearer {token}")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Evaluation deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="messages.success_response"),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="array", @OA\Items(example="Invalid token"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Evaluation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Evaluation not found"),
     *             @OA\Property(property="errors", type="array", @OA\Items())
     *         )
     *     )
     * )
     */

    public function delete($id)
    {
        Evaluation::query()->findOrFail($id)->delete();
        return apiResponse();
    }

}
