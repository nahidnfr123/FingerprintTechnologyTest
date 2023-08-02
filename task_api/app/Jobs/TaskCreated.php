<?php

namespace App\Jobs;

use App\Mail\NewTaskMails;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TaskCreated
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = $this->task->users;
        if (!empty($users)) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NewTaskMails($this->task));
            }
        }
    }
}
