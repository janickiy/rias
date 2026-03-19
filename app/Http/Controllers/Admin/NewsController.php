<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\NewsData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\News\EditRequest;
use App\Http\Requests\Admin\News\StoreRequest;
use App\Models\News;
use App\Repositories\NewsRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.news.index')->with('title', 'Новости');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.news.create_edit', compact('maxUploadFileSize'))->with('title', 'Добавление новости');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $this->imageStorageService->storeResizedImage($request->file('image'), 'news', null, 300);
        }

        $this->newsRepository->create(NewsData::fromArray($validated));

        return redirect()->route('cp.news.index')->with('success', 'Данные успешно добавлены');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = News::findOrFail($id);
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.news.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование новости');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = News::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['image'] = $row->image;

        if ($request->hasFile('image')) {
            $this->imageStorageService->deleteIfExists('news', $row->image);
            $validated['image'] = $this->imageStorageService->storeResizedImage($request->file('image'), 'news', null, 300);
        }

        $this->newsRepository->update($row, NewsData::fromArray($validated));

        return redirect()->route('cp.news.index')->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $news = News::find($request->id);

        if (!$news) {
            return;
        }

        $this->imageStorageService->deleteIfExists('news', $news->image);
        $this->newsRepository->delete($news);
    }
}
