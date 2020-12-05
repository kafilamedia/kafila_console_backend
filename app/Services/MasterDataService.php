<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Departement;
use App\Models\DiscussionTopic;
use App\Models\Issue;
use App\Models\MeetingNote;
use App\Models\User;
use App\Utils\ObjectUtil;
use App\Utils\QueryUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

define('DEFAULT_LIMIT', 10);

class MasterDataService
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function list(WebRequest $request) : WebResponse
    {
        $response = new WebResponse();
        $list = ['count'=>0, 'list'=>[]];
        switch ($request->code) {
            case 'departement':
                $list = $this->getDepartementList($request);
                break;
            case 'user':
                $list = $this->getUserList($request);
                break;
            case 'meeting_note':
                $list = $this->getMeetingNoteList($request->filter, $request->user);
                break;
            case 'discussion_topic':
                $list = $this->getDiscussionTopicList($request->filter, $request->user);
                break;
            case 'issue':
                $list = $this->getIssueList($request->filter, $request->user);
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        $response->count = $list['count'];
        $response->result_list = $list['list'];
        $response->filter = $this->adjustFilterKey($request->filter);
        return $response;
    }

    public static function adjustFilterKey(Filter $filter) : Filter
    {
        foreach ($filter->fieldsFilter as $key => $value) {
            if (Str::contains($key, '.')) {
                unset($filter->fieldsFilter[$key]);
                $adjustedKey = explode('.', $key)[1];
                $filter->fieldsFilter[$adjustedKey] = $value;
            }
        }
        return $filter;
    }

    public function view(WebRequest $request, $id) : WebResponse
    {
        $response = new WebResponse();
        $list = [];
        switch ($request->code) {
            case 'departement':
                $result = $this->getDepartementList($request, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->departement = $result[0];
                    $list = [$response->departement];
                }
                break;
            case 'user':
                $result = $this->getUserList($request, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->user = $result[0];
                    $list = [$response->user];
                }
                break;
            case 'meeting_note':
                $result = $this->getMeetingNoteList($request->filter, $request->user, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->meeting_note = $result[0];
                    $list = [$response->meeting_note];
                }
                break;
            case 'meeting_note':
                $result = $this->getDiscussionTopicList($request->filter, $request->user, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->discussion_topic = $result[0];
                    $list = [$response->discussion_topic];
                }
                break;
            case 'issue':
                $result = $this->getIssueList($request->filter, $request->user, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->issue = $result[0];
                    $list = [$response->issue];
                }
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        $response->result_list = $list;
        return $response;
    }

    public function delete(WebRequest $request, $id) : WebResponse
    {
        $response = new WebResponse();
        $existing = null;
        switch ($request->code) {
            case 'departement':
                $existing = Departement::find($id);
                break;
            case 'user':
                $existing = User::find($id);
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        if (is_null($existing)) {
            throw new Exception("Existing Data Not Found");
        }
        $existing->delete();
        return $response;
    }

    public function store(WebRequest $request, Request $httpRequest) : WebResponse
    {
        $response = new WebResponse();
         
        switch ($request->code) {
            case 'departement':
                $response->departement = $this->storeDepartement($request->departement);
                break;
            case 'user':
                $response->user = $this->storeUser($request->userModel);
                break;
            case 'meeting_note':
                $response->meeting_note = $this->storeMeetingNote($request->meeting_note, $httpRequest->user());
                break;
            case 'discussion_topic':
                $response->meeting_note = $this->storeDiscussionTopic($request->discussion_topic, $httpRequest->user());
                break;
            case 'issue':
                $response->issue = $this->storeIssue($request->issue, $httpRequest->user());
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        
        return $response;
    }
    ////////////////////////////////////// CRUD //////////////////////////////

    public function storeMeetingNote(MeetingNote $requesModel, User $user) : MeetingNote
    {
        $model = new MeetingNote();
        $existing = null;
        if (!is_null($requesModel->id)) {
            $existing = MeetingNote::find($requesModel->id);
            if (!is_null($existing)) {
                $model = $existing;
            } else {
                throw new Exception("existing data Not Found");
            }
        }
        if (is_null($existing)) {
            //if new record, enable to fills these values
            $model->user_id = $user->id;
            $model->date = $requesModel->date;
            $model->place = $requesModel->place;
            $model->departement_id = $user->departement_id;
        }
        //
        $model->save();

        $departement = Departement::find($user->departement_id);
        $model->departement = $departement;
        return $model;
    }

    public function storeDiscussionTopic(DiscussionTopic $requesModel, User $user) : DiscussionTopic
    {
        $model = new DiscussionTopic();
        $model->note_id = $requesModel->note_id;
        $existing = null;
        if (!is_null($requesModel->id)) {
            $existing = DiscussionTopic::find($requesModel->id);
            if (!is_null($existing)) {
                $model = $existing;
            } else {
                throw new Exception("existing data Not Found");
            }
        }

        $meeting_note = MeetingNote::find($model->note_id);
        if (is_null($meeting_note)) {
            throw new Exception("existing note Not Found: ".$model->note_id);
        }
        
        if (is_null($existing)) {
            //if new record, enable to fills these values
            $model->user_id = $user->id;
            $model->date = $meeting_note->date; //date is same as meeting note
            $model->deadline_date = $requesModel->deadline_date;
            $model->departement_id = $meeting_note->departement_id;
            $model->note_id = $requesModel->note_id;
            $model->person_in_charge = $requesModel->person_in_charge;
        }
        //
        $model->content = $requesModel->content;
        $model->decision = $requesModel->decision;

        $model->save();
        
        $departement = Departement::find($user->departement_id);
        $model->departement = $departement;
        return $model;
    }


    public function storeDepartement(Departement $requesModel) : Departement
    {
        $model = new Departement();

        if (!is_null($requesModel->id)) {
            $existing = Departement::find($requesModel->id);
            if (!is_null($existing)) {
                $model = $existing;
            } else {
                throw new Exception("existing data Not Found");
            }
        }

        $model->name = $requesModel->name;
        $model->description = $requesModel->description;
        $model->save();
        return $model;
    }

    public function storeUser(User $requesModel) : User
    {
        if (is_null($requesModel)) {
            throw new Exception("User data not found");
        }
        if (!is_null($requesModel->id)) {
            $existing = User::find($requesModel->id);
            if (is_null($existing)) {
                throw new Exception("existing data Not Found");
            }
        }

        $saved = $this->accountService->saveUser($requesModel, is_null($requesModel->id));
        return $saved;
    }

    public function storeIssue(Issue $requesModel, User $user) : Issue
    {
        $model = new Issue();
        $existing = null;
        if (!is_null($requesModel->id)) {
            $existing = Issue::find($requesModel->id);
            if (!is_null($existing)) {
                $model = $existing;
            } else {
                throw new Exception("existing data Not Found");
            }
        }
        //TODO: determine update record fields
        if (true || is_null($existing)) {
            //if new record, enable to fills these values
            $model->date = $requesModel->date;
            $model->issuer = $requesModel->issuer;
            $model->issue_input = $requesModel->issue_input;
            $model->email = $requesModel->email;
        }
        //
        $model->content = $requesModel->content;
        $model->place = $requesModel->place;
        $model->departement_id = $requesModel->departement_id;

        $model->save();
        $departement = Departement::find($requesModel->departement_id);
        $model->departement = $departement;
        return $model;
    }

    /////////////////// get lists /////////////////////

    public function getUserList(WebRequest $request, $id = null) : array
    {
         
        $query =  DB::table('users')
        ->leftJoin('departements', 'departements.id', '=', 'users.departement_id');
        
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement(), true, 'departement');
        $user_select_fields = QueryUtil::setFillableSelect(new User());
        $select_array = array_merge($user_select_fields, $departement_select_fields);
        
        $query->select('users.id as id', ... $select_array);
        // if ($is_admin) {
        //     $query->where('role', '!=', 'admin');
        // }
        $count = 0;
        if (is_null($id)) {
            $filter = ObjectUtil::adjustFieldFilter(new User(), $request->filter);
            QueryUtil::setFilter($query, $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('users.id');
            
            if ($filter->orderBy == "departement") {
                $filter->orderBy = "departements.name";
            }
            
            QueryUtil::setLimitOffsetOrder($query, $filter);
        } else {
            $query->where('users.id', $id);
        }
        
        $list = $query->get();
        return [
            'list'=>QueryUtil::rowMappedList($list, new User()),
            'count'=>$count
        ];
    }

    public function getMeetingNoteList(Filter $filter, User $user, $id = null) : array
    {
        
        $query =  DB::table('meeting_notes')
        ->leftJoin('departements', 'departements.id', '=', 'meeting_notes.departement_id')
        ->leftJoin('users', 'users.id', '=', 'meeting_notes.user_id');
        
        $meeting_note_select_fields = QueryUtil::setFillableSelect(new MeetingNote());
        //foreign
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement(), true, 'departement');
        $user_select_fields = QueryUtil::setFillableSelect(new User(), true, 'user');
        
        $select_array = array_merge($user_select_fields, $departement_select_fields, $meeting_note_select_fields);
        
        $query->select('meeting_notes.id as id', ... $select_array);
        if (!$user->isAdmin()) {
            $query->where('departements.id', $user->departement_id);
        }
        
        $count = 0;
        if (is_null($id)) {
            // if (isset($filter->fieldsFilter['departement'])) {
            //     $query->where('departements.name', 'like', '%'.$filter->fieldsFilter['departement'].'%');
            // }
            $filter = ObjectUtil::adjustFieldFilter(new MeetingNote(), $filter);
            QueryUtil::setFilter($query, $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('meeting_notes.id');
            
            if ($filter->orderBy == "departement") {
                $filter->orderBy = "departements.name";
            }
            if ($filter->orderBy == "user") {
                $filter->orderBy = "users.name";
            }
            
            QueryUtil::setLimitOffsetOrder($query, $filter);
        } else {
            $query->where('meeting_notes.id', $id);
        }

        $list = $query->get();
        return [
            'list'=>QueryUtil::rowMappedList($list, new MeetingNote()),
            'count'=>$count
        ];
    }

    public function getDepartementList(WebRequest $request, $id = null) : array
    {
        $query =  DB::table('departements');
        
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement());
        
        $query->select('departements.id as id', ... $departement_select_fields);

        $count = 0;
        if (is_null($id)) {
            $filter = ObjectUtil::adjustFieldFilter(new Departement(), $request->filter);
            QueryUtil::setFilter($query, $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('departements.id');

            QueryUtil::setLimitOffsetOrder($query, $filter);
        } else {
            $query->where('id', $id);
        }
        $list = $query->get();
       
        return [
            'list'=>QueryUtil::rowMappedList($list, new Departement()),
            'count'=>$count
        ];
    }

    public function getIssueList(?Filter $filter, User $user, $id = null) : array
    {
        
        $query =  DB::table('issues')
        ->leftJoin('departements', 'departements.id', '=', 'issues.departement_id');
        
        $meeting_note_select_fields = QueryUtil::setFillableSelect(new Issue());
        //foreign
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement(), true, 'departement');
        
        $select_array = array_merge($departement_select_fields, $meeting_note_select_fields);
        
        $query->select('issues.id as id', ... $select_array);
        if (!$user->isAdmin()) {
            $query->where('departements.id', $user->departement_id);
        }
        
        $count = 0;
        if (is_null($id)) {
            // if (isset($filter->fieldsFilter['departement'])) {
            //     $query->where('departements.name', 'like', '%'.$filter->fieldsFilter['departement'].'%');
            // }
            $filter = ObjectUtil::adjustFieldFilter(new Issue(), $filter);
            QueryUtil::setFilter($query, $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('issues.id');
            
            if ($filter->orderBy == "departement") {
                $filter->orderBy = "departements.name";
            }
            
            QueryUtil::setLimitOffsetOrder($query, $filter);
        } else {
            $query->where('issues.id', $id);
        }

        $list = $query->get();
        return [
            'list'=>QueryUtil::rowMappedList($list, new Issue()),
            'count'=>$count
        ];
    }

    public function getDiscussionTopicList(Filter $filter, User $user, $id = null) : array
    {
        
        $query =  DB::table('discussion_topics')
        ->leftJoin('departements', 'departements.id', '=', 'discussion_topics.departement_id')
        ->leftJoin('users', 'users.id', '=', 'discussion_topics.user_id');

        $discussion_topics_select_fields = QueryUtil::setFillableSelect(new DiscussionTopic());
        //foreign
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement(), true, 'departement');
        $user_select_fields = QueryUtil::setFillableSelect(new User(), true, 'user');

        $select_array = array_merge($user_select_fields, $departement_select_fields, $discussion_topics_select_fields);
        
        $query->select('discussion_topics.id as id', ... $select_array);
        if (!$user->isAdmin()) {
            $query->where('departements.id', $user->departement_id);
        }
        
        $count = 0;
        if (is_null($id)) {
            // if (isset($filter->fieldsFilter['departement'])) {
            //     $query->where('departements.name', 'like', '%'.$filter->fieldsFilter['departement'].'%');
            // }
            $filter = ObjectUtil::adjustFieldFilter(new DiscussionTopic(), $filter);
            QueryUtil::setFilter($query, $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('discussion_topics.id');
            
            if ($filter->orderBy == "departement") {
                $filter->orderBy = "departements.name";
            }
            if ($filter->orderBy == "user") {
                $filter->orderBy = "users.name";
            }
            
            QueryUtil::setLimitOffsetOrder($query, $filter);
        } else {
            $query->where('discussion_topics.id', $id);
        }
        $list = $query->get();
        return [
            'list'=>QueryUtil::rowMappedList($list, new DiscussionTopic()),
            'count'=>$count
        ];
    }
}