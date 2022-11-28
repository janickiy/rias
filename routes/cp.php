<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    DashboardController,
    UsersController,
    SettingsController,
    AuthController,
    DataTableController,
    PagesController,
    MenuController,
    NewsController,
    RobotsController,
    AjaxController,
};


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


Route::group(['prefix' => 'cp'], function () {

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('', [DashboardController::class, 'index'])->name('cp.dashbaord.index');

    Route::group(['prefix' => 'pages'], function () {

        // страницы и разделы
        Route::get('', [PagesController::class, 'index'])->name('cp.pages.index');
        Route::get('create', [PagesController::class, 'create'])->name('cp.pages.create');
        Route::post('create', [PagesController::class, 'store'])->name('cp.pages.store');
        Route::get('edit/{id}', [PagesController::class, 'edit'])->name('cp.pages.edit')->where('id', '[0-9]+');
        Route::put('update', [PagesController::class, 'update'])->name('cp.pages.update');
        Route::delete('destroy', [PagesController::class, 'destroy'])->name('cp.pages.destroy');

        // меню
        Route::get('manage-menus',[MenuController::class,'index'])->name('cp.menu.index');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UsersController::class, 'index'])->name('cp.users.index');
        Route::get('create', [UsersController::class, 'create'])->name('cp.users.create');
        Route::post('create', [UsersController::class, 'store'])->name('cp.users.store');
        Route::get('edit/{id}', [UsersController::class, 'edit'])->name('cp.users.edit')->where('id', '[0-9]+');
        Route::put('update', [UsersController::class, 'update'])->name('cp.users.update');
        Route::post('destroy', [UsersController::class, 'destroy'])->name('cp.users.destroy');
    });

    Route::group(['prefix' => 'news'], function () {
        Route::get('', [NewsController::class, 'index'])->name('cp.news.index');
        Route::get('create', [NewsController::class, 'create'])->name('cp.news.create');
        Route::post('create', [NewsController::class, 'store'])->name('cp.news.store');
        Route::get('edit/{id}', [NewsController::class, 'edit'])->name('cp.news.edit')->where('id', '[0-9]+');
        Route::put('update', [NewsController::class, 'update'])->name('cp.news.update');
        Route::post('destroy', [NewsController::class, 'destroy'])->name('cp.news.destroy');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('', [SettingsController::class, 'index'])->name('cp.settings.index');
        Route::get('create', [SettingsController::class, 'create'])->name('cp.settings.create');
        Route::post('create', [SettingsController::class, 'store'])->name('cp.settings.store');
        Route::get('edit/{id}', [SettingsController::class, 'edit'])->name('cp.settings.edit')->where('id', '[0-9]+');
        Route::put('update', [SettingsController::class, 'update'])->name('cp.settings.update');
        Route::post('destroy', [SettingsController::class, 'destroy'])->name('cp.settings.destroy');
    });

    Route::group(['prefix' => 'robots'], function () {
        Route::get('edit', [RobotsController::class, 'edit'])->name('cp.robots.edit');
        Route::put('update', [RobotsController::class, 'update'])->name('cp.robots.update');
    });

    Route::any('ajax', AjaxController::class)->name('cp.ajax.action');

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('users', [DataTableController::class, 'getUsers'])->name('cp.datatable.users');
        Route::any('pages', [DataTableController::class, 'getPages'])->name('cp.datatable.pages');
        Route::any('settings', [DataTableController::class, 'getSettings'])->name('cp.datatable.settings');
        Route::any('news', [DataTableController::class, 'getNews'])->name('cp.datatable.news');
    });

});



