<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\TaskCreated;
use App\Jobs\TaskMailJob;
use App\Models\Task;
use App\Models\User;
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
        $tasks = $user->ownedTasks()->with('users')->latest()->get();
        return $this->successResponse(TaskResource::collection($tasks), 'Task Created Successfully.');
    }

    public function todos(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return $this->errorResponse('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }
        $tasks = $user->assignedTasks()->with('users')->orderBy('due_date', 'ASC')->get();
        return $this->successResponse(TaskResource::collection($tasks), 'Task Created Successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data['status'] = 'open';
        $user = auth()->user();

        $task = $user->ownedTasks()->create($data);
        $userIds = User::whereIn('id', $data['assigned_to'])->pluck('id');
        if (isset($data['assigned_to'])) {
            $task->users()->sync($userIds);
        }
        $task->load('users');
        TaskCreated::dispatch($task);
        return $this->successResponse(new TaskResource($task), 'Task Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): \Illuminate\Http\JsonResponse
    {
        $task->load('users');
        return $this->successResponse(new TaskResource($task), 'Task Shown.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskStoreRequest $request, Task $task): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        if ($task->created_by != auth()->id()) {
            return $this->errorResponse('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $task->update($data);
        $userIds = User::whereIn('id', $data['assigned_to'])->pluck('id');
        if (isset($data['assigned_to'])) {
            $task->users()->sync($userIds);
        }
        $task->load('users');
        return $this->successResponse(new TaskResource($task), 'Task Created Successfully.');
    }

    public function updateStatus(Request $request, Task $task): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:open,in-progress,done',
        ]);

//        if ($task->created_by != auth()->id()) {
//            return $this->errorResponse('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
//        }

        $task->update($data);
        $task->load('users');
        return $this->successResponse(new TaskResource($task), 'Task Created Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {
        $task->delete();
        return $this->successResponse(new TaskResource($task), 'Task Deleted Successfully.');
    }
}
