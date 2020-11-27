<?php

namespace App\Providers;

use App\Services\AccountService;
use App\Services\MeetingNoteService;
use App\Services\StakeHolderManagementService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $accountService = new AccountService();
       
        $this->app->bind(AccountService::class, function ($app) use ($accountService) {
            return $accountService;
        });
        $this->app->bind(MeetingNoteService::class, function ($app) {
            return new MeetingNoteService();
        });
        $this->app->bind(StakeHolderManagementService::class, function ($app) use ($accountService) {
            return new StakeHolderManagementService($accountService);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
    }
}
