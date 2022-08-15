<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskAttachmentFileRequest;
use App\Http\Requests\TaskAttachmentImageRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskAttachmentController extends Controller
{
    public function uploadFile(TaskAttachmentFileRequest $request, Task $task)
    {
        $this->authorize('store', $task);

        $files = $request->file('files');

        $filesNames = [];
        foreach ($files as $file) {
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('files'), $filename);
            $image = $task->attachments()->create([
                'name' => $filename,
            ]);
            $filesNames[] = $image;
        }

        return response()->json($filesNames);
    }

    public function downloadFile($path)
    {
        $file = public_path("/files/$path");
        return response()->download($file);
    }
}

