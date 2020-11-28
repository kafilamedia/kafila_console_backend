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
        $stakeHolderManagementService = new StakeHolderManagementService($accountService);
        $meetingNoteService = new MeetingNoteService($stakeHolderManagementService);

        $this->app->bind(AccountService::class, function ($app) use ($accountService) {
            return $accountService;
        });
        $this->app->bind(StakeHolderManagementService::class, function ($app) use ($stakeHolderManagementService) {
            return $stakeHolderManagementService;
        });
        $this->app->bind(MeetingNoteService::class, function ($app) use ($meetingNoteService) {
            return $meetingNoteService;
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
