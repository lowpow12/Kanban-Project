<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\TaskFile;

class TaskController extends Controller
{
    public function home()//
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        $completed_count = $tasks
            ->where('status', Task::STATUS_COMPLETED)
            ->count();

        $uncompleted_count = $tasks
            ->whereNotIn('status', Task::STATUS_COMPLETED)
            ->count();

        return response()->json([
            'message' => 'Getting data',
            'data' =>  [
            'completed_count' => $completed_count,
            'uncompleted_count' => $uncompleted_count]
        ]);
    }
    public function index()//
    {
        $pageTitle = 'Task List'; 
        if (Gate::allows('viewAnyTask', Task::class)) {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', Auth::user()->id)->get();
        }
        return response()->json([
            'message' => 'Tasks List',
            'data' => $tasks
        ]);
    }

    public function store(Request $request)//
    {
        $request->validate(
            [
                'name' => 'required',
                'due_date' => 'required',
                'status' => 'required',
                'file' => ['max:5000', 'mimes:pdf,jpeg,png'],
            ],
            [
                'file.max' => 'The file size exceed 5 mb',
                'file.mimes' => 'Must be a file of type: pdf,jpeg,png',
            ],
            $request->all()
        );
        
        
        DB::beginTransaction();
        try {
            $task = Task::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'user_id' => Auth::user()->id, 
            ]);
    
            $file = $request->file('file');
            if ($file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storePubliclyAs(
                    'tasks',
                    $file->hashName(),
                    'public'
                );
    
                TaskFile::create([
                    'task_id' => $task->id,
                    'filename' => $filename,
                    'path' => $path,
                ]);
            }
    
            DB::commit();
            
            return response()->json([
                'message' => 'Tasks has been created',
                'data' => $task
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();dd($th);
            return response()->json([
                'message' => 'Tasks cant be created'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(Request $request, $id)//
    {
        $task = Task::findOrFail($id);

        if (Gate::denies('performAsTaskOwner', $task)) {
            Gate::authorize('updateAnyTask', Task::class);
        }

        $task->update([
            'name' => $request->name,
            'detail' => $request->detail,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);
        

        return response()->json([
            'message' => 'Tasks has been updated',
            'data' => $task
        ], Response::HTTP_OK);
    }

    public function destroy($id)//
    {
        $task = Task::findOrFail($id);
        if (Gate::denies('performAsTaskOwner', $task)) {
            Gate::authorize('deleteAnyTask', Task::class);
        }

        $task->delete();
        return response()->json([
            'message' => 'Tasks has been deleted',
        ], Response::HTTP_OK);
    }
}
