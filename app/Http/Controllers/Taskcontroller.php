<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
   

    public function __construct()
    {
       
    }

    public function create($status=null)//
    {
        $pageTitle = 'Create Task';
        

    return view('tasks.create', ['pageTitle' => $pageTitle, 'status'=>$status]);
    }

    public function store(Request $request)//
    {
        $request->validate(
            [
                'name' => 'required',
                'due_date' => 'required',
                'status' => 'required',
            ],
            $request->all()
        );
        
        Task::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'user_id' => Auth::user()->id, 
        ]);

        return redirect()->route('tasks.index');
    }

    public function edit($id)//
    {
            $pageTitle = 'Edit Task';
            $task = Task::findOrFail($id);

            if (Gate::denies('performAsTaskOwner', $task)) {
                Gate::authorize('updateAnyTask', Task::class);
            }

            return view('tasks.edit', ['pageTitle' => $pageTitle, 'task' => $task]);
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
        

        return redirect()->route('tasks.index');
    }

    public function index()//
    {
        $pageTitle = 'Task List'; 
        if (Gate::allows('viewAnyTask', Task::class)) {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', Auth::user()->id)->get();
        }
        return view('tasks.index', [
            'pageTitle' => $pageTitle, 
            'tasks' => $tasks,
        ]);
    }

    public function delete($id)//
    {
        $pageTitle = 'Delete Task';
        $task = Task::findOrFail($id);

        if (Gate::denies('performAsTaskOwner', $task)) {
            Gate::authorize('deleteAnyTask', Task::class);
        }

        return view('tasks.delete', ['pageTitle' => $pageTitle, 'task' => $task]);
    }

    public function destroy($id)//
    {
        $task = Task::findOrFail($id);
        if (Gate::denies('performAsTaskOwner', $task)) {
            Gate::authorize('deleteAnyTask', Task::class);
        }

        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function progress()//
    {
    $title = 'Task Progress';

    if (Gate::allows('viewAnyTask', Task::class)) {
        $tasks = Task::all();
    } else {
        $tasks = Task::where('user_id', Auth::user()->id)->get();
    }
    
    $filteredTasks = $tasks->groupBy('status');

    
    $tasks = [
        Task::STATUS_NOT_STARTED => $filteredTasks->get(
            Task::STATUS_NOT_STARTED, []
        ),
        Task::STATUS_IN_PROGRESS => $filteredTasks->get(
            Task::STATUS_IN_PROGRESS, []
        ),
        Task::STATUS_IN_REVIEW => $filteredTasks->get(
            Task::STATUS_IN_REVIEW, []
        ),
        Task::STATUS_COMPLETED => $filteredTasks->get(
            Task::STATUS_COMPLETED, []
        ),
    ];


    return view('tasks.progress', [
        'pageTitle' => $title,
        'tasks' => $tasks,
    ]);
    }

    public function move(int $id, Request $request)//
    {
    $task = Task::findOrFail($id);
    if (Gate::denies('performAsTaskOwner', $task)) {
        Gate::authorize('updateAnyTask', Task::class);
    }

    $task->update([
        'status' => $request->status,
    ]);

    return redirect()->route('tasks.progress');
    }

    public function moveToTaskList(int $id, Request $request)//
    {
    $task = Task::findOrFail($id);
    if (Gate::denies('performAsTaskOwner', $task)) {
        Gate::authorize('updateAnyTask', Task::class);
    }

    $task->update([
        'status' => $request->status,
    ]);

    return redirect()->route('tasks.index');
    }

    public function home()//
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        $completed_count = $tasks
            ->where('status', Task::STATUS_COMPLETED)
            ->count();

        $uncompleted_count = $tasks
            ->whereNotIn('status', Task::STATUS_COMPLETED)
            ->count();

        return view('home', [
            'completed_count' => $completed_count,
            'uncompleted_count' => $uncompleted_count,
        ]);
    }
}