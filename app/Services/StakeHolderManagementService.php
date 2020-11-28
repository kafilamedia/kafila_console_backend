<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Departement;
use App\Models\MeetingNote;
use App\Models\User;
use App\Utils\ObjectUtil;
use App\Utils\QueryUtil;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

define('DEFAULT_LIMIT', 10);

class StakeHolderManagementService
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
                $list = $this->getMeetingNoteList($request->filter);
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        $response->count = $list['count'];
        $response->result_list = $list['list'];
        $response->filter = $request->filter;
        return $response;
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
                $result = $this->getMeetingNoteList($request->filter, $id)['list'];
                if (sizeof($result) > 0) {
                    $response->user = $result[0];
                    $list = [$response->user];
                }
                break;
            default:
                throw new Exception("Invalid code");
                break;
        }
        $response->result_list = $list;
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
                $response->user = $this->storeUser($request->user);
                break;
            case 'meeting_note':
                $response->meeting_note = $this->storeMeetingNote($request->meeting_note, $httpRequest->user());
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
            $model->user_id = $user->id;
            $model->date = $requesModel->date;
            $model->place = $requesModel->place;
            $model->deadline_date = $requesModel->deadline_date;
            $model->departement_id = $user->departement_id;
            $model->person_in_charge = $requesModel->person_in_charge;
        }
        $model->content = $requesModel->content;
        $model->decision = $requesModel->decision;

        $model->save();
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
        if (!is_null($requesModel->id)) {
            $existing = User::find($requesModel->id);
            if (is_null($existing)) {
                throw new Exception("existing data Not Found");
            }
        }

        $saved = $this->accountService->saveUser($requesModel, is_null($requesModel->id));
        return $saved;
    }

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
            $countQuery = clone $query;
            $count = $countQuery->count('users.id');
            QueryUtil::setFilterLimitOffsetOrder($query, $filter);
        } else {
            $query->where('users.id', $id);
        }
        
        $list = $query->get();
        return [
            'list'=>QueryUtil::rowMappedList($list, new User()),
            'count'=>$count
        ];
    }

    public function getMeetingNoteList(Filter $filter, $id = null) : array
    {
         
        $query =  DB::table('meeting_notes')
        ->leftJoin('departements', 'departements.id', '=', 'meeting_notes.departement_id')
        ->leftJoin('users', 'users.id', '=', 'meeting_notes.user_id');
        
        $departement_select_fields = QueryUtil::setFillableSelect(new Departement(), true, 'departement');
        $user_select_fields = QueryUtil::setFillableSelect(new User(), true, 'user');
        $meeting_note_select_fields = QueryUtil::setFillableSelect(new MeetingNote());
        $select_array = array_merge($user_select_fields, $departement_select_fields, $meeting_note_select_fields);
        
        $query->select('meeting_notes.id as id', ... $select_array);
        $count = 0;
        if (is_null($id)) {
            $filter = ObjectUtil::adjustFieldFilter(new MeetingNote(), $filter);
            $countQuery = clone $query;
            $count = $countQuery->count('meeting_notes.id');
            
            QueryUtil::setFilterLimitOffsetOrder($query, $filter);
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
            $countQuery = clone $query;
            $count = $countQuery->count('departements.id');

            QueryUtil::setFilterLimitOffsetOrder($query, $filter);
        } else {
            $query->where('id', $id);
        }
        $list = $query->get();
       
        return [
            'list'=>QueryUtil::rowMappedList($list, new Departement()),
            'count'=>$count
        ];
    }
}