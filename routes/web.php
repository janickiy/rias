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

Route::get('/page/{slug}', [FrontendController::class, 'page'])->name('frontend.page');

Route::get('/contact', [FrontendController::class,'contact'])->name('frontend.contact');
Route::post('/send-msg', [FrontendController::class,'sendMsg'])->name('frontend.send_msg');


