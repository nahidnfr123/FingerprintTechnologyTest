<?php

namespace App\Providers;

use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Observers\TaskObserver;
use Illuminate\Http\Resources\Json\JsonResource;
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
//        UserResource::withoutWrapping();
        JsonResource::withoutWrapping();
    }
}
