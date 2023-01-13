<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;


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

Route::get('', [FrontendController::class, 'index'])->name('frontend.index');

Route::get('/pages/{slug}', [FrontendController::class, 'page'])->name('frontend.pages');

Route::get('/news/{slug?}', [FrontendController::class, 'news'])->name('frontend.news');

Route::get('/catalog/{slug?}', [FrontendController::class, 'catalog'])->name('frontend.catalog');

Route::get('/product/{slug}', [FrontendController::class, 'product'])->name('frontend.product');

Route::get('/contact', [FrontendController::class,'contact'])->name('frontend.contact');

Route::post('/send-msg', [FrontendController::class,'sendMsg'])->name('frontend.send_msg');




