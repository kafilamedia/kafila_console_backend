<?php

namespace App\Providers;

use App\Services\AccountService;
use App\Services\IssuesService;
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
        $issuesService = new IssuesService($stakeHolderManagementService);

        $this->app->bind(AccountService::class, function ($app) use ($accountService) {
            return $accountService;
        });
        $this->app->bind(StakeHolderManagementService::class, function ($app) use ($stakeHolderManagementService) {
            return $stakeHolderManagementService;
        });
        $this->app->bind(MeetingNoteService::class, function ($app) use ($meetingNoteService) {
            return $meetingNoteService;
        });
        $this->app->bind(IssuesService::class, function ($app) use ($issuesService) {
            return $issuesService;
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
