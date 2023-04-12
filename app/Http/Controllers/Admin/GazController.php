<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Gaz,GazGroup,GazToGroup};
use URL;
use Validator;
use Image;
use Storage;

class GazController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.gaz.index')->with('title', 'Группы газов');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $options = GazGroup::getOption();

        return view('cp.gaz.create_edit', compact('options'))->with('title', 'Добавление газа');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:40',
            'weight' => 'nullable|numeric',
            'chemical_formula' => 'required|max:20',
            'chemical_formula_html' => 'required|max:60',
            'gaz_group_id' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $id = Gaz::create($request->all())->id;

        if ($request->gaz_group_id && $id) {
            foreach ($request->gaz_group_id as $gaz_group_id) {
                if (is_numeric($gaz_group_id)) {
                    GazToGroup::create(['gaz_id' => $id, 'gaz_group_id' => $gaz_group_id]);
                }
            }
        }

        return redirect(URL::route('cp.gaz.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = Gaz::find($id);

        if (!$row) abort(404);


        $options = GazGroup::getOption();

        $gaz_group_id = [];

        foreach ($row->groups as $group) {
            $gaz_group_id[] = $group->id;
        }

        return view('cp.gaz.create_edit', compact('row', 'options', 'gaz_group_id'))->with('title', 'Редактирование газа');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required|max:40',
            'weight' => 'nullable|numeric',
            'chemical_formula' => 'required|max:20',
            'chemical_formula_html' => 'required|max:60',
            'gaz_group_id' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $row = Gaz::find($request->id);

        if (!$row) abort(404);

        $row->title = $request->input('title');
        $row->weight = $request->input('weight');
        $row->chemical_formula = $request->input('chemical_formula');
        $row->chemical_formula_html = $request->input('chemical_formula_html');

        $row->save();

        GazToGroup::where('faz_id', $request->id)->delete();

        if ($request->gaz_group_id) {
            foreach ($request->gaz_group_id as $gaz_group_id) {
                if (is_numeric($gaz_group_id)) {
                    GazToGroup::create(['gaz_id' => $request->id, 'gaz_group_id' => $gaz_group_id]);
                }
            }
        }

        return redirect(URL::route('cp.gaz.index'))->with('success', 'Данные обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        Gaz::find($request->id)->remove();
    }
}
