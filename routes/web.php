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

Route::group(['middleware' => config('menu.middleware')], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    $path = rtrim(config('menu.route_path'));
    Route::post($path . '/addcustommenu', array('as' => 'haddcustommenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@addcustommenu'));
    Route::post($path . '/deleteitemmenu', array('as' => 'hdeleteitemmenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@deleteitemmenu'));
    Route::post($path . '/deletemenug', array('as' => 'hdeletemenug', 'uses' => '\Harimayco\Menu\Controllers\MenuController@deletemenug'));
    Route::post($path . '/createnewmenu', array('as' => 'hcreatenewmenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@createnewmenu'));
    Route::post($path . '/generatemenucontrol', array('as' => 'hgeneratemenucontrol', 'uses' => '\Harimayco\Menu\Controllers\MenuController@generatemenucontrol'));
    Route::post($path . '/updateitem', array('as' => 'hupdateitem', 'uses' => '\Harimayco\Menu\Controllers\MenuController@updateitem'));
});
