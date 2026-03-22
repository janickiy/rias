<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\NewsData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\News\EditRequest;
use App\Http\Requests\Admin\News\StoreRequest;
use App\Repositories\NewsRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * @param NewsRepository $newsRepository
     * @param ImageStorageService $imageStorageService
     */
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.news.index', [
            'title' => 'Новости',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.news.create_edit', [
            'title' => 'Добавление новости',
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $this->prepareDataForStore($request);

        $this->newsRepository->create(NewsData::fromArray($data));

        return redirect()
            ->route('cp.news.index')
            ->with('success', 'Данные успешно добавлены');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->newsRepository->findOrFail($id);

        return view('cp.news.create_edit', [
            'title' => 'Редактирование новости',
            'row' => $row,
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->newsRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $row->image);

        $this->newsRepository->update($row, NewsData::fromArray($data));

        return redirect()
            ->route('cp.news.index')
            ->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $id = $request->integer('id');
        $news = $this->newsRepository->find($id);

        if ($news !== null) {
            $this->imageStorageService->deleteIfExists('news', $news->image);
            $this->newsRepository->delete($news);
        }

        return redirect()
            ->route('cp.news.index')
            ->with('success', 'Информация успешно удалена');
    }

    /**
     * Подготовка данных для создания.
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();
        $data['image'] = $this->uploadImageIfExists($request);

        return $data;
    }

    /**
     * Подготовка данных для обновления.
     * @param EditRequest $request
     * @param string|null $currentImage
     * @return array
     */
    private function prepareDataForUpdate(EditRequest $request, ?string $currentImage): array
    {
        $data = $request->validated();
        $data['image'] = $currentImage;

        $newImage = $this->uploadImageIfExists($request, $currentImage);

        if ($newImage !== null) {
            $data['image'] = $newImage;
        }

        return $data;
    }

    /**
     * Загрузка изображения при наличии файла.
     * @param Request $request
     * @param string|null $oldImage
     * @return string|null
     */
    private function uploadImageIfExists(Request $request, ?string $oldImage = null): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        if ($oldImage !== null) {
            $this->imageStorageService->deleteIfExists('news', $oldImage);
        }

        return $this->imageStorageService->storeResizedImage(
            $request->file('image'),
            'news',
            null,
            300
        );
    }
}
