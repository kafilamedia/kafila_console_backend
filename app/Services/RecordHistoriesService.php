<?php
namespace App\Services;

use App\Dto\Statistic;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Departement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RecordHistoriesService {
    private MasterDataService $masterDataService;
    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function getDashboardStatisticData(WebRequest $request, User $user) :WebResponse
    {
        $statistic = new Statistic();

        $topic_status = $this->getDiscussionTopicStatus($request, $user);
        $statistic->departement_id = $topic_status['departement_id'];
        $statistic->topic_closed_count = $topic_status['closed_count'];
        $statistic->topic_not_closed_count = ($topic_status['count']- $topic_status['closed_count']);
        $statistic->topic_count = $topic_status['count'];
        
        $issue_status = $this->getIssueStatus($request, $user);
        $statistic->issue_closed_count = $issue_status['closed_count'];
        $statistic->issue_not_closed_count = ($issue_status['count']- $issue_status['closed_count']);
        $statistic->issue_count = $issue_status['count'];

        if ($user->isAdmin()) {
            $statistic->departements = Departement::all()->toArray();
        }

        $response = new WebResponse();
        $response->statistic = $statistic;
        return $response;
    }

    public function getDiscussionTopicStatus(WebRequest $request, User $user) : array
    {
        $query =  DB::table('discussion_topics');

        $queryActions =  DB::table('discussion_actions')
        ->leftJoin('discussion_topics', 'discussion_topics.id', '=', 'discussion_actions.topic_id');

        $departement_id = "all";
        if (!$user->isAdmin()) {
            $departement_id = $user->departement_id;
        } elseif ($user->isAdmin() && !is_null($request->filter) && isset($request->filter->fieldsFilter['departement_id'])) {
            $departement_id = $request->filter->fieldsFilter['departement_id'];
        }

        if ($departement_id != "all") {
            $query->where('discussion_topics.departement_id', $departement_id);
            $queryActions->where('discussion_topics.departement_id', $departement_id);
        }

        $count = $query->count();
        $closed_count = $queryActions->count();
        return ['count'=>$count, 'closed_count' => $closed_count, 'departement_id' => $departement_id];
    }

    public function getIssueStatus(WebRequest $request, User $user) : array
    {
        $query =  DB::table('issues');

        $queryActions =  DB::table('followed_up_issues')
        ->leftJoin('issues', 'issues.id', '=', 'followed_up_issues.issue_id');

        $departement_id = "all";
        if (!$user->isAdmin()) {
            $departement_id = $user->departement_id;
        } elseif ($user->isAdmin() && !is_null($request->filter) && isset($request->filter->fieldsFilter['departement_id'])) {
            $departement_id = $request->filter->fieldsFilter['departement_id'];
        }

        if ($departement_id != "all") {
            $query->where('issues.departement_id', $departement_id);
            $queryActions->where('issues.departement_id', $departement_id);
        }

        $count = $query->count();
        $closed_count = $queryActions->count();
        return ['count'=>$count, 'closed_count' => $closed_count, 'departement_id' => $departement_id];
    }
}