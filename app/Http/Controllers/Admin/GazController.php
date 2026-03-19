<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\GazData;
use App\Http\Requests\Admin\Gaz\EditRequest;
use App\Http\Requests\Admin\Gaz\StoreRequest;
use App\Models\Gaz;
use App\Models\GazGroup;
use App\Repositories\GazRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GazController extends Controller
{
    public function __construct(private readonly GazRepository $gazRepository)
    {
    }

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
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->gazRepository->create(GazData::fromArray($request->validated()));

        return redirect()->route('cp.gaz.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = Gaz::findOrFail($id);
        $options = GazGroup::getOption();
        $gaz_group_id = $row->groups->pluck('id')->all();

        return view('cp.gaz.create_edit', compact('row', 'options', 'gaz_group_id'))->with('title', 'Редактирование газа');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = Gaz::findOrFail($request->integer('id'));
        $this->gazRepository->update($row, GazData::fromArray($request->validated()));

        return redirect()->route('cp.gaz.index')->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $row = Gaz::find($request->id);

        if ($row) {
            $this->gazRepository->delete($row);
        }
    }
}
