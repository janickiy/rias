<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductPhotoData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\ProductPhotos\EditRequest;
use App\Http\Requests\Admin\ProductPhotos\StoreRequest;
use App\Repositories\ProductPhotoRepository;
use App\Repositories\ProductRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductPhotosController extends Controller
{
    public function __construct(
        private readonly ProductPhotoRepository $photoRepository,
        private readonly ProductRepository $productRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
        parent::__construct();
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $row = $this->productRepository->findOrFail($product_id);

        return view('cp.product_photos.index', [
            'row' => $row,
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
            'title' => 'Фото оборудования: ' . $row->title,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function upload(StoreRequest $request): RedirectResponse
    {
        $data = $this->prepareDataForStore($request);

        $this->photoRepository->create(
            ProductPhotoData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_photos.index', [
                'product_id' => $request->integer('product_id'),
            ])
            ->with('success', 'Данные успешно добавлены');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->photoRepository->findOrFail($id);

        return view('cp.product_photos.create_edit', [
            'row' => $row,
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
            'title' => 'Редактирование фото: ' . $row->product->title,
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->photoRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $row);

        $this->photoRepository->update(
            $row,
            ProductPhotoData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_photos.index', [
                'product_id' => $row->product_id,
            ])
            ->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $row = $this->photoRepository->find($request->integer('id'));

        if ($row !== null) {
            $productId = $row->product_id;

            $this->deletePhotoFiles($row->origin, $row->thumbnail);
            $this->photoRepository->delete($row);

            return redirect()
                ->route('cp.product_photos.index', [
                    'product_id' => $productId,
                ])
                ->with('success', 'Информация успешно удалена');
        }

        return redirect()
            ->back()
            ->with('error', 'Фото не найдено');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();
        $files = $this->storePhotoFiles($request);

        $data['origin'] = $files['origin'];
        $data['thumbnail'] = $files['thumbnail'];

        return $data;
    }

    /**
     * @param EditRequest $request
     * @param object $photo
     * @return array
     */
    private function prepareDataForUpdate(EditRequest $request, object $photo): array
    {
        $data = $request->validated();
        $data['product_id'] = $photo->product_id;
        $data['origin'] = $photo->origin;
        $data['thumbnail'] = $photo->thumbnail;

        $files = $this->replacePhotoFilesIfExists(
            $request,
            $photo->origin,
            $photo->thumbnail
        );

        if ($files !== null) {
            $data['origin'] = $files['origin'];
            $data['thumbnail'] = $files['thumbnail'];
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function storePhotoFiles(Request $request): array
    {
        return $this->imageStorageService->storeOriginalAndThumbnail(
            $request->file('image'),
            'images',
            300,
            300
        );
    }

    /**
     * @param Request $request
     * @param string|null $oldOrigin
     * @param string|null $oldThumbnail
     * @return array|null
     */
    private function replacePhotoFilesIfExists(
        Request $request,
        ?string $oldOrigin = null,
        ?string $oldThumbnail = null,
    ): ?array {
        if (!$request->hasFile('image')) {
            return null;
        }

        $this->deletePhotoFiles($oldOrigin, $oldThumbnail);

        return $this->storePhotoFiles($request);
    }

    /**
     * @param string|null $origin
     * @param string|null $thumbnail
     * @return void
     */
    private function deletePhotoFiles(?string $origin, ?string $thumbnail): void
    {
        if ($thumbnail !== null) {
            $this->imageStorageService->deleteIfExists('images', $thumbnail);
        }

        if ($origin !== null) {
            $this->imageStorageService->deleteIfExists('images', $origin);
        }
    }
}
