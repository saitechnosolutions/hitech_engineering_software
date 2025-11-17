<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\TaskDataTable;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $receivedTaskPending = Task::with('createdBy', 'assignedTo')->where('assigned_to', Auth::user()->id)->where('status', 'pending')->get();
        $receivedTaskCompleted = Task::with('createdBy', 'assignedTo')->where('assigned_to', Auth::user()->id)->where('status', 'completed')->get();
        $assignedTaskPendings = Task::with('createdBy', 'assignedTo')->where('created_by', Auth::user()->id)->where('status', 'pending')->get();
        $assignedTaskCompletes = Task::with('createdBy', 'assignedTo')->where('created_by', Auth::user()->id)->where('status', 'completed')->get();

        return view('pages.tasks.index', compact('receivedTaskPending', 'receivedTaskCompleted', 'assignedTaskPendings', 'assignedTaskCompletes'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::user()->id)->get();

        return view('pages.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {

        $multipleImages = $request->upload_image;

        $taskImageUrl = [];
        if($multipleImages)
        {
            foreach($multipleImages as $image)
            {
                    $uploadImage = $image;
                    $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
                    $destinationPath = 'task_images/';
                    $uploadImage->move($destinationPath, $originalFilename);
                    $taskImageUrl[] = '/task_images/' . $originalFilename;
            }
        }
        else
            {
                $taskImageUrl = null;
            }



        $tasks = new Task();
        $tasks->task_details = $request->task_details;
        $tasks->title = $request->task_title;
        $tasks->task_date = $request->task_date;
        $tasks->created_by = Auth::user()->id;
        $tasks->assigned_to = $request->assigned_to;
        $tasks->status = 'pending';
        $tasks->task_type = $request->task_type;
        $tasks->priority = $request->priority;
        $tasks->end_date = $request->end_date;
        $tasks->repeating_type = $request->repeating_type;
        $tasks->created_task_images = $taskImageUrl;

        if($tasks->repeating_type == 'daily')
            {
                $tasks->next_run_date = Carbon::parse($request->task_date)->addDay();
            }
            elseif($tasks->repeating_type == 'weekly')
            {
                $tasks->next_run_date = Carbon::parse($request->task_date)->addWeek();
            }
            elseif($tasks->repeating_type == 'monthly')
            {
                $tasks->next_run_date = Carbon::parse($request->task_date)->addMonth();
            }

        $tasks->save();

        return response()->json([
            "status" => 'success',
            "message" => 'Task Created Successfully',
            "redirectTo" => '/tasks'
        ]);
    }

    public function updateTaskStatus(Request $request)
    {

        if ($request->hasFile('upload_image')) {
            $uploadImage = $request->file('upload_image');
            $originalFilename = time() . "-" . str_replace(' ', '_', $uploadImage->getClientOriginalName());
            $destinationPath = 'task_images/';
            $uploadImage->move($destinationPath, $originalFilename);
            $taskImageUrl = '/task_images/' . $originalFilename;
        }
        else
        {
            $taskImageUrl = null;
        }

        $task = Task::find($request->task_id);
        $task->update([
            "comple_task_image" => $taskImageUrl,
            "completed_task_timestamp" => Carbon::now(),
            "status" => $request->task_status
        ]);

        return response()->json([
            "status" => 'success',
            "message" => 'Task Status Updated',
            "redirectTo" => '/tasks'
        ]);
    }
}