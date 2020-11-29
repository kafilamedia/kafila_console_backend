<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 
//AUTO api prefixed
Route::prefix('account')->group(function () {
    Route::post('login', 'Rest\RestAccountController@login');
    Route::post('register', 'Rest\RestAccountController@register');

    Route::post('requestid', 'Rest\RestAccountController@requestId')->name('requestid');
    Route::get('requestid', 'Rest\RestAccountController@requestId')->name('requestid');
});
Route:: group(['prefix' => 'accountdashboard' , 'middleware'=>'auth:api'  ], function () {
    Route::post('user', 'Rest\RestAccountDashboardController@getUser');
    Route::post('logout', 'Rest\RestAccountController@logout');
    
});
Route:: group(['prefix' => 'notes' , 'middleware'=>'auth:api'  ], function () {
    Route::post('list', 'Rest\RestMeetingNotesController@list');
    Route::post('store', 'Rest\RestMeetingNotesController@store');
    Route::post('view/{id}', 'Rest\RestMeetingNotesController@view');
    // Route::post('delete', 'Rest\RestMeetingNotesController@destroy');
});

Route:: group(['prefix' => 'action' , 'middleware'=>'auth:api'  ], function () {
    Route::post('store', 'Rest\RestMeetingNotesController@storeAction');
});
Route:: group(['prefix' => 'masterdata' , 'middleware'=>['auth:api', 'role:admin']  ], function () {
    Route::post('store', 'Rest\RestStakeHolderManagementController@store');
    Route::post('list', 'Rest\RestStakeHolderManagementController@list');
    Route::post('view/{id}', 'Rest\RestStakeHolderManagementController@view');
    Route::post('delete/{id}', 'Rest\RestStakeHolderManagementController@delete');
});
