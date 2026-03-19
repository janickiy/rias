<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\GazGroupData;
use App\Http\Requests\Admin\GazGroup\EditRequest;
use App\Http\Requests\Admin\GazGroup\StoreRequest;
use App\Models\GazGroup;
use App\Repositories\GazGroupRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GazGroupController extends Controller
{
    public function __construct(private readonly GazGroupRepository $gazGroupRepository)
    {
    }

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
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->gazGroupRepository->create(GazGroupData::fromArray($request->validated()));

        return redirect()->route('cp.gaz_group.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = GazGroup::findOrFail($id);

        return view('cp.gaz_group.create_edit', compact('row'))->with('title', 'Редактирование продукции');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = GazGroup::findOrFail($request->integer('id'));
        $this->gazGroupRepository->update($row, GazGroupData::fromArray($request->validated()));

        return redirect()->route('cp.gaz_group.index')->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $row = GazGroup::find($request->id);

        if ($row) {
            $this->gazGroupRepository->delete($row);
        }
    }
}
