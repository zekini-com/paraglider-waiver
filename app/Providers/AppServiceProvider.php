<?php

namespace App\Providers;

use App\Repositories\Contracts\WaiverRepositoryInterface;
use App\Repositories\Contracts\WaiverTextRepositoryInterface;
use App\Repositories\WaiverRepository;
use App\Repositories\WaiverTextRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WaiverRepositoryInterface::class, WaiverRepository::class);
        $this->app->bind(WaiverTextRepositoryInterface::class, WaiverTextRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
