<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskDueDateUpdateRequest;
use App\Http\Requests\TaskShowRequest;
use App\Http\Requests\TaskStatusUpdateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\ToggleArchiveRequest;
use App\Models\Task;
use App\Services\Tag\CreateTags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function list()
    {
        $task = auth()->user()->tasks();

        if (request()->status == 'completed') {
            $task->whereNotNull('completed_at');
        } else if (request()->status == 'archived') {
            $task->whereNotNull('archived_at');
        } else if (request()->status == 'todo') {
            $task->where('status', Task::IN_COMPLETE);
        } else {
            $task->where('status', '<>', Task::IN_COMPLETE);
        }

        if (!empty(request()->filter_due_date_from) && !empty(request()->filter_due_date_to)) {
            $task->whereBetween('due_date', [
                Carbon::parse(request()->filter_due_date_from)->format('Y-m-d'),
                Carbon::parse(request()->filter_due_date_to)->format('Y-m-d')
            ]);
        }

        if (!empty(request()->filter_completed_from) && !empty(request()->filter_completed_to)) {
            $task->whereDate('completed_at','>=', request()->filter_completed_from)
                  ->whereDate('completed_at','<=', request()->filter_completed_to);
        }

        if (!empty(request()->filter_archived_from) && !empty(request()->filter_archived_to)) {
            $task->whereDate('archived_at','>=', request()->filter_archived_from)
                ->whereDate('archived_at','<=', request()->filter_archived_to);
        }

        if (!is_null(request()->filter_priority)) {
            $task->where('priority', '=', request()->filter_priority);
        }

        if (!empty(request()->filter_tile_and_description)) {
            $keyword = request()->filter_tile_and_description;
            $task->where(function ($query) use ($keyword)  {
                $query->orWhere('title','like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            });

        }
        $paginatedTask = $task->orderBy(request()->sort_field, request()->sort_direction)->paginate(12);
        return response()->json($paginatedTask);

    }

    public function store(TaskCreateRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());

        if (!empty($request->tags)) {
            (new CreateTags($task, $request->tags))->handle();
        }

        if ($request->has('files')) {
            $files = $request->file('files');
            foreach($files as $file) {
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('files'), $filename);
                $task->attachments()->create(['name' => $filename]);
            }
        }

        return response()->json($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json($task);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $updateTask = $task->update($request->validated());

        return response()->json($updateTask);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->noContent();
    }

    public function updateTaskStatus(TaskStatusUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $result = $task->update([
            'status' => $request->status,
            'completed_at' => $request->status == Task::COMPLETE ? now() : null,
        ]);

        return response()->json($result);
    }

    public function updateDueDate(TaskDueDateUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $result = $task->update(['due_date' => $request->due_date ?? null]);

        return response()->json($result);
    }

    public function toggleArchived(ToggleArchiveRequest $request,Task $task)
    {
        $this->authorize('update', $task);

        $archivedAtValue = $request->status == 'archived' ? now() : null;
        $result = $task->update(['archived_at' => $archivedAtValue]);

        return response()->json($result);
    }
}
