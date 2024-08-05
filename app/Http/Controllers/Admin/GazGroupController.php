<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Models\{
    GazGroup,
};
use App\Http\Requests\Admin\GazGroup\StoreRequest;
use App\Http\Requests\Admin\GazGroup\EditRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Image;
use Storage;

class GazGroupController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.gaz_group.index')->with('title', 'Группы газов');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.gaz_group.create_edit')->with('title', 'Добавление группы газов');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest$request): RedirectResponse
    {
        GazGroup::create($request->all());

        return redirect()->route('cp.gaz_group.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = GazGroup::find($id);

        if (!$row) abort(404);

        return view('cp.gaz_group.create_edit', compact('row'))->with('title', 'Редактирование продукции');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = GazGroup::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->name_ru = $request->input('name_ru');
        $row->save();

        return redirect()->route('cp.gaz_group.index')->with('success', 'Данные обновлены');

    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        GazGroup::find($request->id)->remove();
    }
}
