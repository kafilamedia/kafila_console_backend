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
        $response = new WebResponse();
        
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

        $topic_count = $query->count();
        $statistic = new Statistic();
        $statistic->departement_id = $departement_id;
        if (0 == $topic_count) {
            $response->statistic = $statistic;
            return $response;
        }
        
        $closed_count = $queryActions->count();

        $statistic->topic_closed_count = $closed_count;
        $statistic->topic_not_closed_count = ($topic_count- $closed_count);
        $statistic->topic_count = $topic_count;

        if ($user->isAdmin()) {
            $statistic->departements = Departement::all()->toArray();
        }

        $response->statistic = $statistic;
        return $response;
    }
}