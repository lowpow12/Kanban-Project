<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskFile;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TaskFileController extends Controller
{
    public function store($task_id, Request $request)
    {
        $task = Task::findOrFail($task_id);
    
        $request->validate(
            [
                'file' => ['required', 'mimes:pdf,jpeg,png', 'max:5000'],
            ],
            [
                'file.max' => 'The file size exceed 5 mb',
                'file.mimes' => 'Must be a file of type: pdf,jpeg,png',
            ],
            $request->all()
        );
    
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storePubliclyAs('tasks', $file->hashName(), 'public');
    
        TaskFile::create([
            'task_id' => $task->id,
            'filename' => $filename,
            'path' => $path,
        ]);
    
        return response()->json([
            'message' => 'File has been created',
            'data' => $task->id
        ], Response::HTTP_OK);
    }

    public function destroy($task_id, $id)
{
    $file = TaskFile::findOrFail($id);

    Storage::disk('public')->delete($file->path);
    $file->delete();
    return response()->json([
        'message' => 'File has been deleted',
    ], Response::HTTP_OK);
}
}
