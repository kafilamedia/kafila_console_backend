<?php

namespace App\Providers;

use App\Services\AccountService;
use App\Services\DiscussionTopicService;
use App\Services\IssuesService;
use App\Services\MeetingNoteService;
use App\Services\MasterDataService;
use App\Services\RecordHistoriesService;
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
        
        $masterDataService = new MasterDataService($accountService);
        $discussionTopicService = new DiscussionTopicService($masterDataService);
        $meetingNoteService = new MeetingNoteService($masterDataService, $discussionTopicService);
        $issuesService = new IssuesService($masterDataService);
        $recordHistoryService = new RecordHistoriesService($masterDataService);

        $this->app->bind(AccountService::class, function ($app) use ($accountService) {
            return $accountService;
        });
        $this->app->bind(MasterDataService::class, function ($app) use ($masterDataService) {
            return $masterDataService;
        });
        $this->app->bind(MeetingNoteService::class, function ($app) use ($meetingNoteService) {
            return $meetingNoteService;
        });
        $this->app->bind(DiscussionTopicService::class, function ($app) use ($discussionTopicService) {
            return $discussionTopicService;
        });
        $this->app->bind(IssuesService::class, function ($app) use ($issuesService) {
            return $issuesService;
        });
        $this->app->bind(RecordHistoriesService::class, function ($app) use ($recordHistoryService) {
            return $recordHistoryService;
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
