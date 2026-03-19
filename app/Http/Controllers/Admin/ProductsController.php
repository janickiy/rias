<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\Products\EditRequest;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Models\Catalog;
use App\Models\Products;
use App\Repositories\ProductRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
    }

    public function index(): View
    {
        return view('cp.products.index')->with('title', 'Продукция');
    }

    public function create(): View
    {
        $options = Catalog::getOption();
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.products.create_edit', compact('options', 'maxUploadFileSize'))->with('title', 'Добавление продукции');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $files = $this->imageStorageService->storeOriginalAndThumbnail($request->file('image'), 'products', null, 300);
            $validated['origin'] = $files['origin'];
            $validated['thumbnail'] = $files['thumbnail'];
        }

        $this->productRepository->create(ProductData::fromArray($validated));

        return redirect()->route('cp.products.index')->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id): View
    {
        $row = Products::findOrFail($id);
        $options = Catalog::getOption();
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.products.create_edit', compact('row', 'options', 'maxUploadFileSize'))->with('title', 'Редактирование продукции');
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = Products::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['thumbnail'] = $row->thumbnail;
        $validated['origin'] = $row->origin;

        if ($request->hasFile('image')) {
            $this->imageStorageService->deleteIfExists('products', $row->thumbnail);
            $this->imageStorageService->deleteIfExists('products', $row->origin);

            $files = $this->imageStorageService->storeOriginalAndThumbnail($request->file('image'), 'products', null, 300);
            $validated['origin'] = $files['origin'];
            $validated['thumbnail'] = $files['thumbnail'];
        }

        $this->productRepository->update($row, ProductData::fromArray($validated));

        return redirect()->route('cp.products.index')->with('success', 'Данные обновлены');
    }

    public function destroy(Request $request): void
    {
        $row = Products::find($request->id);

        if ($row) {
            $this->productRepository->delete($row);
        }
    }
}
