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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sites','SiteController@index');
Route::get('/agents/{option}','AgentsController@index');
Route::get('/farmers/{phone}','FarmersController@index');
Route::get('/harvest','HarvestController@index');
Route::get('/harvestReport/{phone}','HarvestController@index');
Route::get('/balance/{phone}','BalanceController@index');
Route::get("sitereport",'PaymentsController@siteReport');
Route::get("reports",'reportController@reports');
Route::get("farmersReport/{option}",'FarmersController@AdminReport');
Route::get("adminHarvestReport/{option}",'HarvestController@AdminReport');
Route::get('/adminBalanceReport','BalanceController@AdminReport');
Route::get('/transactions','PaymentsController@transactions');
Route::post('/addfarmer','FarmersController@create');
Route::post('/addpayment','PaymentsController@create');
Route::post('/addHarvest','HarvestController@create');
Route::post('/addAgent','AgentsController@create');
Route::post('/addsite','SiteController@create');
Route::post('/login','UserController@login');
Route::post('/registerUser','UserController@store');
Route::post("deleteSite/{id}","SiteController@delete");
Route::post("deleteAgent/{id}","AgentsController@delete");
Route::post("status/{id}","AgentsController@status");
Route::post("/sendSms","messageController@sendSms");
Route::post("/createPrice","PriceController@create");
Route::get("/price","PriceController@index");
Route::post("/changePassword","UserController@changePassword");
Route::post('/contacts','ContactController@create');


