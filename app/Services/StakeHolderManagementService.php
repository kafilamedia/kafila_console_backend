<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Departement;
use Exception;

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
        $list = [];
        switch ($request->code) {
            case 'departement':
                $list = $this->getDepartementList();
                break;
            
            default:
                throw new Exception("Invalid code");
                break;
        }
        $response->result_list = $list;
        return $response;
    }
    public function store(WebRequest $request) : WebResponse
    {
        $response = new WebResponse();
         
        switch ($request->code) {
            case 'departement':
                $response->departement = $this->storeDepartement($request->departement);
                break;
            
            default:
                throw new Exception("Invalid code");
                break;
        }
        
        return $response;
    } 
    ////////////////////////////////////// CRUD //////////////////////////////

    public function storeDepartement(Departement $requesModel) : Departement
    {
        $model = new Departement();

        if (!is_null($requesModel->id)) {
            $existing = Departement::find($requesModel->id);
            if (!is_null($existing)) {
                $model = $existing;
            } else {
                throw new Exception("Department Not Found");
            }
        }

        $model->name = $requesModel->name;
        $model->description = $requesModel->description;
        $model->save();
        return $model;
    }
    public function getDepartementList() : array
    {
        $depatements = Departement::all();
        return $depatements->toArray();
    }
}