<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductDocumentData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\ProductDocuments\EditRequest;
use App\Http\Requests\Admin\ProductDocuments\StoreRequest;
use App\Models\ProductDocuments;
use App\Models\Products;
use App\Repositories\ProductDocumentRepository;
use App\Services\DocumentStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductDocumentsController extends Controller
{
    public function __construct(
        private readonly ProductDocumentRepository $documentRepository,
        private readonly DocumentStorageService $documentStorageService,
    ) {
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $row = Products::findOrFail($product_id);

        return view('cp.product_documents.index', compact('product_id'))->with('title', 'Список документации: ' . $row->title);
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.product_documents.create_edit', compact('product_id', 'maxUploadFileSize'))->with('title', 'Добавление документации');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['path'] = $this->documentStorageService->store($request->file('file'), 'documents');

        $this->documentRepository->create(ProductDocumentData::fromArray($validated));

        return redirect()->route('cp.product_documents.index', ['product_id' => $request->integer('product_id')])->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = ProductDocuments::findOrFail($id);
        $product_id = $row->product_id;

        return view('cp.product_documents.create_edit', compact('row', 'product_id'))->with('title', 'Редактирование списка документации');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductDocuments::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['path'] = $row->path;
        $validated['product_id'] = $row->product_id;

        if ($request->hasFile('file')) {
            $this->documentStorageService->deleteIfExists('documents', $row->path);
            $validated['path'] = $this->documentStorageService->store($request->file('file'), 'documents');
        }

        $this->documentRepository->update($row, ProductDocumentData::fromArray($validated));

        return redirect()->route('cp.product_documents.index', ['product_id' => $row->product_id])->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $row = ProductDocuments::find($request->id);

        if (!$row) {
            return;
        }

        $this->documentStorageService->deleteIfExists('documents', $row->path);
        $this->documentRepository->delete($row);
    }
}
