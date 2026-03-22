<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;


use App\DTO\Admin\ProductParameterData;
use App\Http\Requests\Admin\ProductParameters\EditRequest;
use App\Http\Requests\Admin\ProductParameters\StoreRequest;
use App\Http\Requests\Admin\ProductParameters\DeleteRequest;
use App\Repositories\ProductParameterRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductParametersController extends Controller
{
    public function __construct(
        private readonly ProductParameterRepository $parameterRepository,
        private readonly ProductRepository $productRepository,
    ) {
        parent::__construct();
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $product = $this->productRepository->findOrFail($product_id);
        $parameters = $this->parameterRepository->getByProductId($product_id);

        return view('cp.product_parameters.index', [
            'parameters' => $parameters,
            'product_id' => $product_id,
            'title' => 'Технические характеристики: ' . $product->title,
        ]);
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        $this->productRepository->findOrFail($product_id);

        return view('cp.product_parameters.create_edit', [
            'product_id' => $product_id,
            'title' => 'Добавление параметра',
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->parameterRepository->create(
            ProductParameterData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.product_parameters.index', [
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
        $row = $this->parameterRepository->findOrFail($id);

        return view('cp.product_parameters.create_edit', [
            'row' => $row,
            'product_id' => $row->product_id,
            'title' => 'Редактирование параметра',
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->parameterRepository->findOrFail($request->id);
        $data = $request->validated();
        $data['product_id'] = $row->product_id;

        $this->parameterRepository->update(
            $row,
            ProductParameterData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_parameters.index', [
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
        $row = $this->parameterRepository->find($request->id);

        if ($row !== null) {
            $productId = $row->product_id;

            $this->parameterRepository->delete($row);

            return redirect()
                ->route('cp.product_parameters.index', [
                    'product_id' => $productId,
                ])
                ->with('success', 'Информация успешно удалена');
        }

        return redirect()
            ->back()
            ->with('error', 'Параметр не найден');
    }
}
