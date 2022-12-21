<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Photoalbums;
use Illuminate\Support\Facades\Validator;
use URL;

class PhotoAlbumsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.photoalbums.index')->with('title', 'Новости');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('cp.photoalbums.create_edit')->with('title', 'Добавление фотоальбома');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Photoalbums::create($request->all());

        return redirect(URL::route('cp.photoalbums.index'))->with('success', 'Данные успешно добавлены');

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $row = Photoalbums::find($id);

        if (!$row) abort(404);

        return view('cp.photoalbums.create_edit', compact('row'))->with('title', 'Редактирование фотоальбома');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = Photoalbums::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->save();

        return redirect(URL::route('cp.photoalbums.index'))->with('success', 'Данные успешно обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        Photoalbums::where('id', $request->id)->delete();
    }
}
