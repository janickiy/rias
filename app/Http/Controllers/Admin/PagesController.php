<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\PageData;
use App\Http\Requests\Admin\Pages\EditRequest;
use App\Http\Requests\Admin\Pages\StoreRequest;
use App\Repositories\PageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.pages.index', [
            'title' => 'Страницы и разделы',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.pages.create_edit', [
            'title' => 'Добавление раздела',
            'options' => $this->pageRepository->getParentOptions(),
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->pageRepository->create(
            PageData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.pages.index')
            ->with('success', 'Данные успешно добавлены');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->pageRepository->findOrFail($id);

        return view('cp.pages.create_edit', [
            'title' => 'Редактирование раздела',
            'row' => $row,
            'options' => $this->pageRepository->getParentOptions(),
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->pageRepository->findOrFail($request->id);

        $this->pageRepository->update(
            $row,
            PageData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.pages.index')
            ->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $row = $this->pageRepository->find($request->id);

        if ($row !== null) {
            $this->pageRepository->delete($row);
        }

        return redirect()
            ->route('cp.pages.index')
            ->with('success', 'Информация успешно удалена');
    }
}
