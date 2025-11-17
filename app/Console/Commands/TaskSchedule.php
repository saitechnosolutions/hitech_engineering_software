<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Console\Command;

class TaskSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check repeating tasks and create new ones automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Fetch repeating tasks due to run now
        $tasks = Task::where('task_type', 'repeating_task')
            ->whereDate('next_run_date', '<=', $now->toDateString())
            ->get();



        foreach ($tasks as $task) {

            // Check end_date condition
            if ($task->end_date && Carbon::parse($task->end_date)->lt($now)) {
                // Stop repeating if end date passed
                $task->task_type = 'repeating_task';
                $task->save();
                continue;
            }

            // Create new repeated task row
            $taskInfo = Task::create([
                'title' => $task->title,
                'task_details' => $task->task_details,
                'task_type' => 'repeating_task',
                'task_date' => $now->toDateString(),
                'created_by' => $task->created_by,
                'assigned_to' => $task->assigned_to,
                'priority' => $task->priority,
                'repeating_type' => $task->repeating_type,
                'end_date' => $task->end_date,
            ]);

            if($task->repeating_type == 'daily')
            {
                $taskInfo->next_run_date = Carbon::parse($task->next_run_date)->addDay();
            }
            elseif($task->repeating_type == 'weekly')
            {
                $taskInfo->next_run_date = Carbon::parse($task->next_run_date)->addWeek();
            }
            elseif($task->repeating_type == 'monthly')
            {
                $taskInfo->next_run_date = Carbon::parse($task->next_run_date)->addMonth();
            }

            $taskInfo->save();
        }

        $this->info("Repeating tasks processed successfully.");
    }
}
