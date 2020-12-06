<?php
namespace App\Services;

use App\Dto\Statistic;
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

    public function getDashboardStatisticData(User $user) :WebResponse
    {
        $response = new WebResponse();
        $departement_id = $user->departement_id;
        $query =  DB::table('discussion_topics')->where('departement_id', $departement_id);
        $topic_count = $query->count();
        if (0 == $topic_count) {
            $response->statistic = new Statistic();
            return $response;
        }

        $queryActions =  DB::table('discussion_actions')
        ->leftJoin('discussion_topics', 'discussion_topics.id', '=', 'discussion_actions.topic_id');
        $queryActions->where('discussion_topics.departement_id', $departement_id);
        $closed_count = $queryActions->count();

        $statistic = new Statistic();
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