<?php

namespace App\Http\Controllers\Admin;

class DashboardController extends Controller
{
    public function index()
    {
        return view('cp.dashboard.index')->with('title', 'Главная');
    }
}
