<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\GazGroupData;
use App\Http\Requests\Admin\GazGroup\EditRequest;
use App\Http\Requests\Admin\GazGroup\StoreRequest;
use App\Repositories\GazGroupRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GazGroupController extends Controller
{
    public function __construct(
        private readonly GazGroupRepository $gazGroupRepository,
    )
    {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.gaz_group.index', [
            'title' => 'Группы газов',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.gaz_group.create_edit', [
            'title' => 'Добавление группы газов',
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->gazGroupRepository->create(
            GazGroupData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.gaz_group.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->gazGroupRepository->findOrFail($id);

        return view('cp.gaz_group.create_edit', [
            'title' => 'Редактирование группы газов',
            'row' => $row,
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->gazGroupRepository->findOrFail($request->id);

        $this->gazGroupRepository->update(
            $row,
            GazGroupData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.gaz_group.index')
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $row = $this->gazGroupRepository->find($request->id);

        if ($row !== null) {
            $this->gazGroupRepository->delete($row);
        }

        return redirect()
            ->route('cp.gaz_group.index')
            ->with('success', 'Информация успешно удалена');
    }
}
