<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\CatalogData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\Catalog\EditRequest;
use App\Http\Requests\Admin\Catalog\StoreRequest;
use App\Repositories\CatalogRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * @param CatalogRepository $catalogRepository
     * @param ImageStorageService $imageStorageService
     */
    public function __construct(
        private readonly CatalogRepository $catalogRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.catalog.index', [
            'title' => 'Категории',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.catalog.create_edit', [
            'title' => 'Добавление категории',
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

        $this->catalogRepository->create(CatalogData::fromArray($data));

        return redirect()
            ->route('cp.catalog.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->catalogRepository->findOrFail($id);

        return view('cp.catalog.create_edit', [
            'title' => 'Редактирование категории',
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
        $row = $this->catalogRepository->findOrFail($request->id);
        $data = $this->prepareDataForUpdate($request, $row->image);

        $this->catalogRepository->update($row, CatalogData::fromArray($data));

        return redirect()
            ->route('cp.catalog.index')
            ->with('success', 'Информация успешно обновлена');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $id = $request->id;
        $row = $this->catalogRepository->find($id);

        if ($row !== null) {
            $this->catalogRepository->delete($row);
        }

        return redirect()
            ->route('cp.catalog.index')
            ->with('success', 'Информация успешно удалена');
    }

    /**
     * Подготовка данных для создания.
     * @param StoreRequest $request
     * @return array
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
            $this->imageStorageService->deleteIfExists('catalog', $oldImage);
        }

        return $this->imageStorageService->storeResizedImage(
            $request->file('image'),
            'catalog',
            null,
            400
        );
    }
}
