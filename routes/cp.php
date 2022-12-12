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
    CatalogController,
    SitemapController,
    ProductsController,
    FeedbackController,
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

    });

    Route::get('feedback',FeedbackController::class)->name('cp.feedback.index');
    Route::get('manage-menus',[MenuController::class,'index'])->name('cp.menu.index');

    Route::group(['prefix' => 'catalog'], function () {
        Route::get('',[CatalogController::class, 'index'])->name('cp.catalog.index');
        Route::get('create/{parent_id?}',[CatalogController::class, 'create'])->name('cp.catalog.create')->where('parent_id', '[0-9]+');
        Route::post('store',[CatalogController::class, 'store'])->name('cp.catalog.store');
        Route::get('edit/{id}',[CatalogController::class, 'edit'])->name('cp.catalog.edit')->where('id', '[0-9]+');
        Route::put('update',[CatalogController::class, 'update'])->name('cp.catalog.update');
        Route::get('delete/{id}',[CatalogController::class, 'delete'])->name('cp.catalog.delete')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UsersController::class, 'index'])->name('cp.users.index');
        Route::get('create', [UsersController::class, 'create'])->name('cp.users.create');
        Route::post('create', [UsersController::class, 'store'])->name('cp.users.store');
        Route::get('edit/{id}', [UsersController::class, 'edit'])->name('cp.users.edit')->where('id', '[0-9]+');
        Route::put('update', [UsersController::class, 'update'])->name('cp.users.update');
        Route::post('destroy', [UsersController::class, 'destroy'])->name('cp.users.destroy');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('', [ProductsController::class, 'index'])->name('cp.products.index');
        Route::get('create', [ProductsController::class, 'create'])->name('cp.products.create');
        Route::post('create', [ProductsController::class, 'store'])->name('cp.products.store');
        Route::get('edit/{id}', [ProductsController::class, 'edit'])->name('cp.products.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductsController::class, 'update'])->name('cp.products.update');
        Route::post('destroy', [ProductsController::class, 'destroy'])->name('cp.products.destroy');
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

    Route::group(['prefix' => 'sitemap'], function () {
        Route::get('', [SitemapController::class, 'index'])->name('cp.sitemap.index');
        Route::get('export', [SitemapController::class, 'export'])->name('cp.sitemap.export');
        Route::get('import', [SitemapController::class, 'importForm'])->name('cp.sitemap.import_form');
        Route::post('import', [SitemapController::class, 'import'])->name('cp.sitemap.import');
    });

    Route::any('ajax', AjaxController::class)->name('cp.ajax.action');

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('users', [DataTableController::class, 'getUsers'])->name('cp.datatable.users');
        Route::any('pages', [DataTableController::class, 'getPages'])->name('cp.datatable.pages');
        Route::any('products', [DataTableController::class, 'getProducts'])->name('cp.datatable.products');
        Route::any('settings', [DataTableController::class, 'getSettings'])->name('cp.datatable.settings');
        Route::any('news', [DataTableController::class, 'getNews'])->name('cp.datatable.news');
        Route::any('feedback', [DataTableController::class, 'getFeedback'])->name('cp.datatable.feedback');
    });

});



