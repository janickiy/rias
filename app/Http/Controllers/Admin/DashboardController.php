<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\Pages;
use App\Models\Products;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $users = User::query()->count();
        $news = News::query()->count();
        $pages = Pages::query()->count();
        $products = Products::query()->count();

        return view('cp.dashboard.index', compact('users', 'news', 'pages', 'products'))->with('title', 'Главная');
    }
}
