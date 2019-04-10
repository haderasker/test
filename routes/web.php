<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get("home", "\App\Http\Controllers\HomeController@index");

Route::group(['middleware' => ['handelServer' , 'auth']], function () {

    Route::get('/', function () {
        return redirect("admin/dashboard");
    });

    Route::post('admin/paginate', '\App\Http\Controllers\Admin\PaginateController@paginateNumber');

    //servers
    Route::get("admin/servers", "\App\Http\Controllers\Admin\ServersController@index");
    Route::get("admin/server/create", "\App\Http\Controllers\Admin\ServersController@createServer");
    Route::get("admin/server/check", "\App\Http\Controllers\Admin\ServersController@checkServer");
    Route::get("admin/server/save", "\App\Http\Controllers\Admin\ServersController@addServer");
    Route::get("admin/server/edit/{serverId}", "\App\Http\Controllers\Admin\ServersController@editServer");
    Route::post("admin/server/update/", "\App\Http\Controllers\Admin\ServersController@updateServer");

    //codes
    Route::get("admin/codes", "\App\Http\Controllers\Admin\CodesController@index");
    Route::post("admin/codes", "\App\Http\Controllers\Admin\CodesController@filterStatusCodes");
    Route::post("admin/code/activate_hma", "\App\Http\Controllers\Admin\CodesController@activateHMA");
    Route::get("admin/code/activate_global/{code}", "\App\Http\Controllers\Admin\CodesController@getActivateGlobal");
    Route::post("admin/code/activate_global", "\App\Http\Controllers\Admin\CodesController@activateGlobal");
    Route::get("admin/codes/export", "\App\Http\Controllers\Admin\CodesController@indexExport");
    Route::get('excelview',array('uses'=>'\App\Http\Controllers\Admin\CodesController@exportCodes'));

    //dashboard
    Route::get("admin/dashboard", "\App\Http\Controllers\Admin\DashboardController@index");
    Route::get("admin/server/info", "\App\Http\Controllers\Admin\DashboardController@serverInfoResult");
    Route::get("admin/server/sync_streaming_servers", "\App\Http\Controllers\Admin\DashboardController@syncStreamingServers");
    Route::post("admin/change/api/url", "\App\Http\Controllers\Admin\DashboardController@changeApiUrl");
    Route::post("admin/change/all_config", "\App\Http\Controllers\Admin\DashboardController@changeConfig");

    //profile update
    Route::get("user/edit", "\App\Http\Controllers\ProfileController@editProfile");
    Route::post("user/update", "\App\Http\Controllers\ProfileController@updateProfile");

   //statistic
    Route::get("admin/server/statistic", "\App\Http\Controllers\Admin\StatisticController@index");

});