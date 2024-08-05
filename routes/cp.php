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
    ProductParametersController,
    ProductPhotosController,
    ProductVideosController,
    ProductDocumentsController,
    GazGroupController,
    GazController,
    SeoController,
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
        Route::get('', [PagesController::class, 'index'])->name('cp.pages.index');
        Route::get('create', [PagesController::class, 'create'])->name('cp.pages.create');
        Route::post('store', [PagesController::class, 'store'])->name('cp.pages.store');
        Route::get('edit/{id}', [PagesController::class, 'edit'])->name('cp.pages.edit')->where('id', '[0-9]+');
        Route::put('update', [PagesController::class, 'update'])->name('cp.pages.update');
        Route::post('destroy', [PagesController::class, 'destroy'])->name('cp.pages.destroy');
    });

    Route::get('feedback',FeedbackController::class)->name('cp.feedback.index');
    Route::get('manage-menus',[MenuController::class,'index'])->name('cp.menu.index')->middleware(['permission:admin|moderator']);

    Route::group(['prefix' => 'catalog'], function () {
        Route::get('',[CatalogController::class, 'index'])->name('cp.catalog.index')->middleware(['permission:admin|moderator']);
        Route::get('create',[CatalogController::class, 'create'])->name('cp.catalog.create')->middleware(['permission:admin|moderator']);
        Route::post('store',[CatalogController::class, 'store'])->name('cp.catalog.store')->middleware(['permission:admin|moderator']);
        Route::get('edit/{id}',[CatalogController::class, 'edit'])->name('cp.catalog.edit')->where('id', '[0-9]+');
        Route::put('update',[CatalogController::class, 'update'])->name('cp.catalog.update')->middleware(['permission:admin|moderator']);
        Route::post('destroy',[CatalogController::class, 'destroy'])->name('cp.catalog.destroy')->middleware(['permission:admin|moderator']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UsersController::class, 'index'])->name('cp.users.index')->middleware(['permission:admin']);
        Route::get('create', [UsersController::class, 'create'])->name('cp.users.create')->middleware(['permission:admin']);
        Route::post('store', [UsersController::class, 'store'])->name('cp.users.store')->middleware(['permission:admin']);
        Route::get('edit/{id}', [UsersController::class, 'edit'])->name('cp.users.edit')->where('id', '[0-9]+');
        Route::put('update', [UsersController::class, 'update'])->name('cp.users.update')->middleware(['permission:admin']);
        Route::post('destroy', [UsersController::class, 'destroy'])->name('cp.users.destroy')->middleware(['permission:admin']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('', [ProductsController::class, 'index'])->name('cp.products.index');
        Route::get('create', [ProductsController::class, 'create'])->name('cp.products.create');
        Route::post('store', [ProductsController::class, 'store'])->name('cp.products.store');
        Route::get('edit/{id}', [ProductsController::class, 'edit'])->name('cp.products.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductsController::class, 'update'])->name('cp.products.update');
        Route::post('destroy', [ProductsController::class, 'destroy'])->name('cp.products.destroy');
    });

    Route::group(['prefix' => 'gaz-group'], function () {
        Route::get('', [GazGroupController::class, 'index'])->name('cp.gaz_group.index')->middleware(['permission:admin|moderator']);
        Route::get('create', [GazGroupController::class, 'create'])->name('cp.gaz_group.create')->middleware(['permission:admin|moderator']);
        Route::post('store', [GazGroupController::class, 'store'])->name('cp.gaz_group.store')->middleware(['permission:admin|moderator']);
        Route::get('edit/{id}', [GazGroupController::class, 'edit'])->name('cp.gaz_group.edit')->where('id', '[0-9]+')->middleware(['permission:admin|moderator']);
        Route::put('update', [GazGroupController::class, 'update'])->name('cp.gaz_group.update')->middleware(['permission:admin|moderator']);
        Route::post('destroy', [GazGroupController::class, 'destroy'])->name('cp.gaz_group.destroy')->middleware(['permission:admin|moderator']);
    });

    Route::group(['prefix' => 'gaz'], function () {
        Route::get('', [GazController::class, 'index'])->name('cp.gaz.index')->middleware(['permission:admin|moderator']);
        Route::get('create', [GazController::class, 'create'])->name('cp.gaz.create')->middleware(['permission:admin|moderator']);
        Route::post('store', [GazController::class, 'store'])->name('cp.gaz.store')->middleware(['permission:admin|moderator']);
        Route::get('edit/{id}', [GazController::class, 'edit'])->name('cp.gaz.edit')->where('id', '[0-9]+')->middleware(['permission:admin|moderator']);
        Route::put('update', [GazController::class, 'update'])->name('cp.gaz.update')->middleware(['permission:admin|moderator']);
        Route::post('destroy', [GazController::class, 'destroy'])->name('cp.gaz.destroy')->middleware(['permission:admin|moderator']);
    });

    Route::group(['prefix' => 'product-parameters'], function () {
        Route::get('{product_id}', [ProductParametersController::class, 'index'])->name('cp.product_parameters.index')->where('product_id', '[0-9]+');
        Route::get('create/{product_id}', [ProductParametersController::class, 'create'])->name('cp.product_parameters.create')->where('product_id', '[0-9]+');
        Route::post('store', [ProductParametersController::class, 'store'])->name('cp.product_parameters.store');
        Route::get('edit/{id}', [ProductParametersController::class, 'edit'])->name('cp.product_parameters.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductParametersController::class, 'update'])->name('cp.product_parameters.update');
        Route::post('destroy', [ProductParametersController::class, 'destroy'])->name('cp.product_parameters.destroy');
    });

    Route::group(['prefix' => 'product-photos'], function () {
        Route::get('{product_id}', [ProductPhotosController::class, 'index'])->name('cp.product_photos.index')->where('product_id', '[0-9]+');
        Route::post('upload', [ProductPhotosController::class, 'upload'])->name('cp.product_photos.upload');
        Route::get('edit/{id}', [ProductPhotosController::class, 'edit'])->name('cp.product_photos.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductPhotosController::class, 'update'])->name('cp.product_photos.update');
        Route::post('destroy', [ProductPhotosController::class, 'destroy'])->name('cp.product_photos.destroy');
    });

    Route::group(['prefix' => 'product-videos'], function () {
        Route::get('{product_id}', [ProductVideosController::class, 'index'])->name('cp.product_videos.index')->where('product_id', '[0-9]+');
        Route::get('create/{product_id}', [ProductVideosController::class, 'create'])->name('cp.product_videos.create')->where('product_id', '[0-9]+');
        Route::post('store', [ProductVideosController::class, 'store'])->name('cp.product_videos.store');
        Route::get('edit/{id}', [ProductVideosController::class, 'edit'])->name('cp.product_videos.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductVideosController::class, 'update'])->name('cp.product_videos.update');
        Route::post('destroy', [ProductVideosController::class, 'destroy'])->name('cp.product_videos.destroy');
    });

    Route::group(['prefix' => 'product-documents'], function () {
        Route::get('{product_id}', [ProductDocumentsController::class, 'index'])->name('cp.product_documents.index')->where('product_id', '[0-9]+');
        Route::get('create/{product_id}', [ProductDocumentsController::class, 'create'])->name('cp.product_documents.create')->where('product_id', '[0-9]+');
        Route::post('store', [ProductDocumentsController::class, 'store'])->name('cp.product_documents.store');
        Route::get('edit/{id}', [ProductDocumentsController::class, 'edit'])->name('cp.product_documents.edit')->where('id', '[0-9]+');
        Route::put('update', [ProductDocumentsController::class, 'update'])->name('cp.product_documents.update');
        Route::post('destroy', [ProductDocumentsController::class, 'destroy'])->name('cp.product_documents.destroy');
    });

    Route::group(['prefix' => 'news'], function () {
        Route::get('', [NewsController::class, 'index'])->name('cp.news.index');
        Route::get('create', [NewsController::class, 'create'])->name('cp.news.create');
        Route::post('store', [NewsController::class, 'store'])->name('cp.news.store');
        Route::get('edit/{id}', [NewsController::class, 'edit'])->name('cp.news.edit')->where('id', '[0-9]+');
        Route::put('update', [NewsController::class, 'update'])->name('cp.news.update');
        Route::post('destroy', [NewsController::class, 'destroy'])->name('cp.news.destroy');
    });

    Route::group(['prefix' => 'seo'], function () {
        Route::get('', [SeoController::class, 'index'])->name('cp.seo.index')->middleware(['permission:admin|moderator']);
        Route::get('edit/{id}', [SeoController::class, 'edit'])->name('cp.seo.edit')->where('id', '[0-9]+')->middleware(['permission:admin|moderator']);
        Route::put('update', [SeoController::class, 'update'])->name('cp.seo.update')->middleware(['permission:admin|moderator']);
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('', [SettingsController::class, 'index'])->name('cp.settings.index')->middleware(['permission:admin']);
        Route::get('create/{type}', [SettingsController::class, 'create'])->name('cp.settings.create')->middleware(['permission:admin']);
        Route::post('store', [SettingsController::class, 'store'])->name('cp.settings.store')->middleware(['permission:admin']);
        Route::get('edit/{id}', [SettingsController::class, 'edit'])->name('cp.settings.edit')->where('id', '[0-9]+')->middleware(['permission:admin']);
        Route::put('update', [SettingsController::class, 'update'])->name('cp.settings.update')->middleware(['permission:admin']);
        Route::post('destroy', [SettingsController::class, 'destroy'])->name('cp.settings.destroy')->middleware(['permission:admin']);
    });

    Route::group(['prefix' => 'robots'], function () {
        Route::get('edit', [RobotsController::class, 'edit'])->name('cp.robots.edit')->middleware(['permission:admin|moderator']);
        Route::put('update', [RobotsController::class, 'update'])->name('cp.robots.update')->middleware(['permission:admin|moderator']);
    });

    Route::group(['prefix' => 'sitemap'], function () {
        Route::get('', [SitemapController::class, 'index'])->name('cp.sitemap.index')->middleware(['permission:admin|moderator']);
        Route::get('export', [SitemapController::class, 'export'])->name('cp.sitemap.export')->middleware(['permission:admin|moderator']);
        Route::get('import', [SitemapController::class, 'importForm'])->name('cp.sitemap.import_form')->middleware(['permission:admin|moderator']);
        Route::post('import', [SitemapController::class, 'import'])->name('cp.sitemap.import')->middleware(['permission:admin|moderator']);
    });

    Route::any('ajax', AjaxController::class)->name('cp.ajax.action');

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('catalog', [DataTableController::class, 'getCatalog'])->name('cp.datatable.catalog')->middleware(['permission:admin|moderator']);
        Route::any('users', [DataTableController::class, 'getUsers'])->name('cp.datatable.users')->middleware(['permission:admin']);
        Route::any('pages', [DataTableController::class, 'getPages'])->name('cp.datatable.pages');
        Route::any('products', [DataTableController::class, 'getProducts'])->name('cp.datatable.products');
        Route::any('settings', [DataTableController::class, 'getSettings'])->name('cp.datatable.settings')->middleware(['permission:admin']);
        Route::any('news', [DataTableController::class, 'getNews'])->name('cp.datatable.news');
        Route::any('feedback', [DataTableController::class, 'getFeedback'])->name('cp.datatable.feedback');
        Route::any('product-photos/{product_id}', [DataTableController::class, 'getPhotos'])->name('cp.datatable.product_photos')->where('product_id', '[0-9]+');
        Route::any('product-videos/{product_id}', [DataTableController::class, 'getVideos'])->name('cp.datatable.product_videos')->where('product_id', '[0-9]+');
        Route::any('product-documents/{product_id}', [DataTableController::class, 'getDocuments'])->name('cp.datatable.product_documents')->where('product_id', '[0-9]+');
        Route::any('product-parameters/{product_id}', [DataTableController::class, 'getProductParameters'])->name('cp.datatable.product_parameters')->where('product_id', '[0-9]+');
        Route::any('gaz-group', [DataTableController::class, 'getGazGroup'])->name('cp.datatable.gaz_group')->middleware(['permission:admin|moderator']);
        Route::any('gaz', [DataTableController::class, 'getGaz'])->name('cp.datatable.gaz')->middleware(['permission:admin|moderator']);
        Route::any('seo', [DataTableController::class, 'getSeo'])->name('cp.datatable.seo')->middleware(['permission:admin|moderator']);
    });

});



