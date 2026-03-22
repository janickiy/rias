<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;


use App\DTO\Admin\ProductData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\Products\EditRequest;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Repositories\CatalogRepository;
use App\Repositories\ProductRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
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
        return view('cp.products.index', [
            'title' => 'Продукция',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.products.create_edit', [
            'title' => 'Добавление продукции',
            'options' => $this->catalogRepository->getOptions(),
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

        $this->productRepository->create(
            ProductData::fromArray($data)
        );

        return redirect()
            ->route('cp.products.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->productRepository->findOrFail($id);

        return view('cp.products.create_edit', [
            'title' => 'Редактирование продукции',
            'row' => $row,
            'options' => $this->catalogRepository->getOptions(),
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->productRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $row->origin, $row->thumbnail);

        $this->productRepository->update(
            $row,
            ProductData::fromArray($data)
        );

        return redirect()
            ->route('cp.products.index')
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $row = $this->productRepository->find($request->integer('id'));

        if ($row !== null) {
            $this->deleteProductImages($row->origin, $row->thumbnail);
            $this->productRepository->delete($row);
        }

        return redirect()
            ->route('cp.products.index')
            ->with('success', 'Информация успешно удалена');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();

        $files = $this->storeImagesIfExists($request);

        if ($files !== null) {
            $data['origin'] = $files['origin'];
            $data['thumbnail'] = $files['thumbnail'];
        }

        return $data;
    }

    /**
     * @param EditRequest $request
     * @param string|null $currentOrigin
     * @param string|null $currentThumbnail
     * @return array
     */
    private function prepareDataForUpdate(
        EditRequest $request,
        ?string $currentOrigin,
        ?string $currentThumbnail,
    ): array {
        $data = $request->validated();
        $data['origin'] = $currentOrigin;
        $data['thumbnail'] = $currentThumbnail;

        $files = $this->replaceImagesIfExists(
            $request,
            $currentOrigin,
            $currentThumbnail
        );

        if ($files !== null) {
            $data['origin'] = $files['origin'];
            $data['thumbnail'] = $files['thumbnail'];
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    private function storeImagesIfExists(Request $request): ?array
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $this->imageStorageService->storeOriginalAndThumbnail(
            $request->file('image'),
            'products',
            null,
            300
        );
    }

    /**
     * @param Request $request
     * @param string|null $oldOrigin
     * @param string|null $oldThumbnail
     * @return array|null
     */
    private function replaceImagesIfExists(
        Request $request,
        ?string $oldOrigin = null,
        ?string $oldThumbnail = null,
    ): ?array {
        if (!$request->hasFile('image')) {
            return null;
        }

        $this->deleteProductImages($oldOrigin, $oldThumbnail);

        return $this->storeImagesIfExists($request);
    }

    /**
     * @param string|null $origin
     * @param string|null $thumbnail
     * @return void
     */
    private function deleteProductImages(?string $origin, ?string $thumbnail): void
    {
        if ($thumbnail !== null) {
            $this->imageStorageService->deleteIfExists('products', $thumbnail);
        }

        if ($origin !== null) {
            $this->imageStorageService->deleteIfExists('products', $origin);
        }
    }
}
