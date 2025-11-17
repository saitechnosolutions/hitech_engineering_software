<?php

namespace App\Providers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $receivedTaskPending = Task::with('createdBy', 'assignedTo')
                ->where('assigned_to', Auth::id())
                ->where('status', 'pending')
                ->get();

            $view->with('pendingCount', $receivedTaskPending->count());
            $view->with('taskPendingDetails', $receivedTaskPending);
        } else {
            $view->with('pendingCount', 0);
            $view->with('taskPendingDetails', collect());
        }
    });
    }
}