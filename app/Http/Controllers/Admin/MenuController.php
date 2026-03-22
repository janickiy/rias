<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('cp.menu.index', [
            'title' => 'Меню',
        ]);
    }
}
