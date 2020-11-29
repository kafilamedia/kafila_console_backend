<?php

namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\User;
use App\Utils\ObjectUtil;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
{
    public function register(WebRequest $request) : WebResponse
    {
        $requestModel = $request->userModel;
        if (is_null($requestModel)) {
            throw new Exception("User data not found");
        }
        $savedUser = $this->saveUser($requestModel);
        $response = new WebResponse();
        $response->user = $savedUser;
        return $response;
    }

    public function logout(User $requestUser) : WebResponse
    {
        $user = User::find($requestUser->id);
        $user->api_token = null;
        $user->save();
        $response = new WebResponse();
        return $response;
    }

    public function saveUser(User $requestModel, bool $new = true) : User
    {
        $user = new User();
        if (!$new) {
            $user = User::find($requestModel->id);
            if (is_null($user)) {
                throw new Exception("Existing data not found");
            }
        }
        $user->email = $requestModel->email;
        $user->name =($requestModel->name);
        $user->display_name = ($requestModel->display_name);
        $user->password = Hash::make(($requestModel->password));
        $user->departement_id = ($requestModel->departement_id);
        if (!$new) {
            # code... not updating ROLE
        } else {
            $user->role = ('user');
        }
        
        $user->save();
        return $user;
    }

    public function loginAttemp(WebRequest $request) : WebResponse
    {
        $user = $request->user;
        $response = new WebResponse();
        $cred = ['email' => $user->email, 'password' => $user->password];

        $dbUser = User::where(['email' => $user->email])->first();
        if (is_null($dbUser)) {
            throw new Exception("Email not found");
        }
        if (Auth::attempt($cred)) {
            
            $token = Str::random(60);
            $hashedToken = hash('sha256', $token);
            $user = $this->updateApiToken(Auth::user()->id, $hashedToken);
            $response->user = $user;
            // $request->session()->put('api_token', $hashedToken);
        } else {
            throw new Exception("Login Failed");
        }
        return $response;
    }

    private function updateApiToken($id, string $token)
    {
        $user = User::find($id);
        $user->api_token = $token;
        $user->save();
        return $user;
    }
}