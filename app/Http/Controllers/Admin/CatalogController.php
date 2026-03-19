<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\CatalogData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\Catalog\EditRequest;
use App\Http\Requests\Admin\Catalog\StoreRequest;
use App\Models\Catalog;
use App\Repositories\CatalogRepository;
use App\Services\ImageStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CatalogRepository $catalogRepository,
        private readonly ImageStorageService $imageStorageService,
    ) {
    }

    public function index(): View
    {
        return view('cp.catalog.index')->with('title', 'Категории');
    }

    public function create(): View
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('maxUploadFileSize'))->with('title', 'Добавление категории');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $this->imageStorageService->storeResizedImage($request->file('image'), 'catalog', null, 400);
        }

        $this->catalogRepository->create(CatalogData::fromArray($validated));

        return redirect()->route('cp.catalog.index')->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id): View
    {
        $row = Catalog::findOrFail($id);
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование категории');
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = Catalog::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['image'] = $row->image;

        if ($request->hasFile('image')) {
            $this->imageStorageService->deleteIfExists('catalog', $row->image);
            $validated['image'] = $this->imageStorageService->storeResizedImage($request->file('image'), 'catalog', null, 400);
        }

        $this->catalogRepository->update($row, CatalogData::fromArray($validated));

        return redirect()->route('cp.catalog.index')->with('success', 'Информация успешно обновлена');
    }

    public function destroy(Request $request): void
    {
        $row = Catalog::find($request->id);

        if ($row) {
            $this->catalogRepository->delete($row);
        }
    }
}
