<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use URL;

class SitemapController extends Controller
{

    public function index()
    {
        return view('cp.sitemap.index')->with('title', 'Загрузка и выгрузка файла карты сайта sitemap.xml');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $file = public_path(). "/sitemap.xml";

        $headers = ['Content-Type: text/xml'];

        return \Response::download($file, 'sitemap.xml', $headers);
    }
}
