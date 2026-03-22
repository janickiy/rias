<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\GazData;
use App\Http\Requests\Admin\Gaz\EditRequest;
use App\Http\Requests\Admin\Gaz\StoreRequest;
use App\Repositories\GazGroupRepository;
use App\Repositories\GazRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GazController extends Controller
{
    public function __construct(
        private readonly GazRepository      $gazRepository,
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
        return view('cp.gaz.index', [
            'title' => 'Группы газов',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.gaz.create_edit', [
            'title' => 'Добавление газа',
            'options' => $this->gazGroupRepository->getOptions(),
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->gazRepository->create(
            GazData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.gaz.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->gazRepository->findOrFail($id);

        return view('cp.gaz.create_edit', [
            'title' => 'Редактирование газа',
            'row' => $row,
            'options' => $this->gazGroupRepository->getOptions(),
            'gaz_group_id' => $this->gazGroupRepository->getIdsByGaz($row),
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->gazRepository->findOrFail($request->id);

        $this->gazRepository->update(
            $row,
            GazData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.gaz.index')
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $row = $this->gazRepository->find($request->id);

        if ($row !== null) {
            $this->gazRepository->delete($row);
        }

        return redirect()
            ->route('cp.gaz.index')
            ->with('success', 'Информация успешно удалена');
    }
}
