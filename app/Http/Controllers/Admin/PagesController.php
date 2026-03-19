<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\PageData;
use App\Http\Requests\Admin\Pages\EditRequest;
use App\Http\Requests\Admin\Pages\StoreRequest;
use App\Models\Pages;
use App\Repositories\PageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function __construct(private readonly PageRepository $pageRepository)
    {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.pages.index')->with('title', 'Страницы и разделы');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $options = Pages::orderBy('id')->published()->pluck('title', 'id')->all();

        return view('cp.pages.create_edit', compact('options'))->with('title', 'Добавление раздела');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->pageRepository->create(PageData::fromArray($request->validated()));

        return redirect()->route('cp.pages.index')->with('success', 'Данные успешно добавлены');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = Pages::findOrFail($id);
        $options = Pages::orderBy('id')->published()->pluck('title', 'id')->all();

        return view('cp.pages.create_edit', compact('row', 'options'))->with('title', 'Редактирование раздела');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = Pages::findOrFail($request->integer('id'));
        $this->pageRepository->update($row, PageData::fromArray($request->validated()));

        return redirect()->route('cp.pages.index')->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $row = Pages::find($request->id);

        if ($row) {
            $this->pageRepository->delete($row);
        }
    }
}
