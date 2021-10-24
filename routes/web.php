<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix'=>'api'], function (){
    Route::group(['prefix'=>'tip'], function (){
        Route :: post("/insert" , [\App\Http\Controllers\TipsController::class , 'InsertTip'] );
        Route :: post("/update" , [\App\Http\Controllers\TipsController::class , 'UpdateTip'] );
        Route :: post("/delete" , [\App\Http\Controllers\TipsController::class , 'DeleteTip'] );
        Route :: post("/get_one" , [\App\Http\Controllers\TipsController::class , 'GetOneTip'] );
        Route :: post("/get_all" , [\App\Http\Controllers\TipsController::class , 'GetAllTip'] );

    });

});
