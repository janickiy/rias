<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RobotsController extends Controller
{
    /**
     * @return View
     */
    public function edit(): View
    {
        $file = File::get(public_path('robots.txt'));

        return view('cp.robots.edit', compact('file'))->with('title', 'Редактирование Robots.txt');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'content' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        File::put(public_path('robots.txt'), $request->input('content'));

        return redirect()->route('cp.robots.edit')->with('success', 'Данные успешно обновлены');
    }
}
