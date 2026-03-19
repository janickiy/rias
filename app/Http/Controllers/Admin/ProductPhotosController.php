<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductPhotoData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\ProductPhotos\EditRequest;
use App\Http\Requests\Admin\ProductPhotos\StoreRequest;
use App\Models\ProductPhotos;
use App\Models\Products;
use App\Repositories\ProductPhotoRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductPhotosController extends Controller
{
    public function __construct(
        private readonly ProductPhotoRepository $photoRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
    }

    public function index(int $product_id): View
    {
        $row = Products::findOrFail($product_id);
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.product_photos.index', compact('row', 'maxUploadFileSize'))->with('title', 'Фото оборудования: ' . $row->title);
    }

    public function upload(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $files = $this->imageStorageService->storeOriginalAndThumbnail($request->file('image'), 'images', 300, 300);
        $validated['origin'] = $files['origin'];
        $validated['thumbnail'] = $files['thumbnail'];

        $this->photoRepository->create(ProductPhotoData::fromArray($validated));

        return redirect()->route('cp.product_photos.index', ['product_id' => $request->integer('product_id')])->with('success', 'Данные успешно добавлены');
    }

    public function edit(int $id): View
    {
        $row = ProductPhotos::findOrFail($id);
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.product_photos.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование фото: ' . $row->product->title);
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductPhotos::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['product_id'] = $row->product_id;
        $validated['origin'] = $row->origin;
        $validated['thumbnail'] = $row->thumbnail;

        if ($request->hasFile('image')) {
            $this->imageStorageService->deleteIfExists('images', $row->thumbnail);
            $this->imageStorageService->deleteIfExists('images', $row->origin);
            $files = $this->imageStorageService->storeOriginalAndThumbnail($request->file('image'), 'images', 300, 300);
            $validated['origin'] = $files['origin'];
            $validated['thumbnail'] = $files['thumbnail'];
        }

        $this->photoRepository->update($row, ProductPhotoData::fromArray($validated));

        return redirect()->route('cp.product_photos.index', ['product_id' => $row->product_id])->with('success', 'Данные успешно обновлены');
    }

    public function destroy(Request $request): void
    {
        $row = ProductPhotos::find($request->id);

        if (!$row) {
            return;
        }

        $this->imageStorageService->deleteIfExists('images', $row->thumbnail);
        $this->imageStorageService->deleteIfExists('images', $row->origin);
        $this->photoRepository->delete($row);
    }
}
