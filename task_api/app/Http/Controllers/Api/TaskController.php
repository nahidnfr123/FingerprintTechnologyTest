<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TaskController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
//            return $this->unauthorized();
            return $this->errorResponse('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }
        $tasks = $user->createdTasks;
        $tasks->load('assignedTo');
        return $this->successResponse(TaskResource::collection($tasks), 'Task Created Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        //
    }
}
