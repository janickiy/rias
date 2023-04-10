<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Models\{
    GazGroup,
    GazToGroup
};

use URL;
use Validator;
use Image;
use Storage;

class GazGroupController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.gaz_group.index')->with('title', 'Группы газов');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('cp.gaz_group.create_edit')->with('title', 'Добавление группы газов');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'name_ru' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        GazGroup::create($request->all());

        return redirect(URL::route('cp.gaz_group.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = GazGroup::find($id);

        if (!$row) abort(404);


        return view('cp.gaz_group.create_edit', compact('row'))->with('title', 'Редактирование продукции');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'name_ru' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $row = Products::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->name_ru = $request->input('name_ru');
        $row->save();

        return redirect(URL::route('cp.gaz_group.index'))->with('success', 'Данные обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $row = GazGroup::find($request->id);

        if ($row) {
            $row->gaz()->delete();
            GazToGroup::where('gaz_group_id', $request->id)->delete();
            $row->delete();
        }
    }
}
