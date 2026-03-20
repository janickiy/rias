<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductDocumentData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\ProductDocuments\EditRequest;
use App\Http\Requests\Admin\ProductDocuments\StoreRequest;
use App\Http\Requests\Admin\ProductDocuments\DeleteRequest;
use App\Repositories\ProductDocumentRepository;
use App\Repositories\ProductRepository;
use App\Services\DocumentStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductDocumentsController extends Controller
{
    public function __construct(
        private readonly ProductDocumentRepository $documentRepository,
        private readonly ProductRepository $productRepository,
        private readonly DocumentStorageService $documentStorageService,
    ) {
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $product = $this->productRepository->findOrFail($product_id);

        return view('cp.product_documents.index', [
            'product_id' => $product_id,
            'title' => 'Список документации: ' . $product->title,
        ]);
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        return view('cp.product_documents.create_edit', [
            'product_id' => $product_id,
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
            'title' => 'Добавление документации',
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $this->prepareDataForStore($request);

        $this->documentRepository->create(
            ProductDocumentData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_documents.index', [
                'product_id' => $request->integer('product_id'),
            ])
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->documentRepository->findOrFail($id);

        return view('cp.product_documents.create_edit', [
            'row' => $row,
            'product_id' => $row->product_id,
            'maxUploadFileSize' => StringHelper::maxUploadFileSize(),
            'title' => 'Редактирование списка документации',
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->documentRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $row->path, $row->product_id);

        $this->documentRepository->update(
            $row,
            ProductDocumentData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_documents.index', [
                'product_id' => $row->product_id,
            ])
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param DeleteRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteRequest $request): RedirectResponse
    {
        $row = $this->documentRepository->find($request->integer('id'));

        if ($row !== null) {
            $productId = $row->product_id;

            $this->documentStorageService->deleteIfExists('documents', $row->path);
            $this->documentRepository->delete($row);

            return redirect()
                ->route('cp.product_documents.index', [
                    'product_id' => $productId,
                ])
                ->with('success', 'Информация успешно удалена');
        }

        return redirect()
            ->back()
            ->with('error', 'Документ не найден');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();
        $data['path'] = $this->documentStorageService->store(
            $request->file('file'),
            'documents'
        );

        return $data;
    }

    /**
     * @param EditRequest $request
     * @param string|null $currentPath
     * @param int $productId
     * @return array
     */
    private function prepareDataForUpdate(
        EditRequest $request,
        ?string $currentPath,
        int $productId,
    ): array {
        $data = $request->validated();
        $data['path'] = $currentPath;
        $data['product_id'] = $productId;

        $newPath = $this->replaceDocumentIfExists($request, $currentPath);

        if ($newPath !== null) {
            $data['path'] = $newPath;
        }

        return $data;
    }

    /**
     * @param Request $request
     * @param string|null $oldPath
     * @return string|null
     */
    private function replaceDocumentIfExists(Request $request, ?string $oldPath = null): ?string
    {
        if (!$request->hasFile('file')) {
            return null;
        }

        if ($oldPath !== null) {
            $this->documentStorageService->deleteIfExists('documents', $oldPath);
        }

        return $this->documentStorageService->store(
            $request->file('file'),
            'documents'
        );
    }
}
