<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Gaz,GazGroup,GazToGroup};
use App\Http\Requests\Admin\Gaz\StoreRequest;
use App\Http\Requests\Admin\Gaz\EditRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Image;
use Storage;

class GazController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.gaz.index')->with('title', 'Группы газов');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $options = GazGroup::getOption();

        return view('cp.gaz.create_edit', compact('options'))->with('title', 'Добавление газа');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $id = Gaz::create($request->all())->id;

        if ($request->gaz_group_id && $id) {
            foreach ($request->gaz_group_id as $gaz_group_id) {
                if (is_numeric($gaz_group_id)) {
                    GazToGroup::create(['gaz_id' => $id, 'gaz_group_id' => $gaz_group_id]);
                }
            }
        }

        return redirect()->route('cp.gaz.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
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
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = Gaz::find($request->id);

        if (!$row) abort(404);

        $row->title = $request->input('title');
        $row->weight = $request->input('weight');
        $row->chemical_formula = $request->input('chemical_formula');
        $row->chemical_formula_html = $request->input('chemical_formula_html');
        $row->save();

        GazToGroup::where('gaz_id', $request->id)->delete();

        if ($request->gaz_group_id) {
            foreach ($request->gaz_group_id as $gaz_group_id) {
                if (is_numeric($gaz_group_id)) {
                    GazToGroup::create(['gaz_id' => $request->id, 'gaz_group_id' => $gaz_group_id]);
                }
            }
        }

        return redirect()->route('cp.gaz.index')->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        Gaz::find($request->id)->remove();
    }
}
