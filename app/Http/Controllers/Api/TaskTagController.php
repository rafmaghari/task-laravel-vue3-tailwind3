<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskCreateTagRequest;
use App\Models\Task;
use App\Models\TaskTag;

class TaskTagController extends Controller
{
    public function store(TaskCreateTagRequest $request)
    {
        $task = Task::findOrFail($request->task_id);
        $this->authorize('store', $task);

        $taskTag = $task->tags()->create(['name' => $request->tag]);

        return response()->json($taskTag);
    }

    public function destroy(TaskTag $tag)
    {
        $this->authorize('delete', $tag->task);

        $tag->delete();

        return response()->noContent();
    }
}
