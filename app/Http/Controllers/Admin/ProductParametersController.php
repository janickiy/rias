<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductParameterData;
use App\Http\Requests\Admin\ProductParameters\EditRequest;
use App\Http\Requests\Admin\ProductParameters\StoreRequest;
use App\Models\ProductParameters;
use App\Models\Products;
use App\Repositories\ProductParameterRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductParametersController extends Controller
{
    public function __construct(private readonly ProductParameterRepository $parameterRepository)
    {
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        Products::findOrFail($product_id);
        $parameters = ProductParameters::where('product_id', $product_id)->get();

        return view('cp.product_parameters.index', compact('parameters', 'product_id'))->with('title', 'Технические характеристики');
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        Products::findOrFail($product_id);

        return view('cp.product_parameters.create_edit', compact('product_id'))->with('title', 'Добавление параметра');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->parameterRepository->create(ProductParameterData::fromArray($request->validated()));

        return redirect()->route('cp.product_parameters.index', ['product_id' => $request->integer('product_id')])->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = ProductParameters::findOrFail($id);
        $product_id = $row->product_id;

        return view('cp.product_parameters.create_edit', compact('row', 'product_id'))->with('title', 'Редактирование параметра');
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductParameters::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['product_id'] = $row->product_id;

        $this->parameterRepository->update($row, ProductParameterData::fromArray($validated));

        return redirect()->route('cp.product_parameters.index', ['product_id' => $row->product_id])->with('success', 'Данные обновлены');
    }

    public function destroy(Request $request): void
    {
        $row = ProductParameters::find($request->id);

        if ($row) {
            $this->parameterRepository->delete($row);
        }
    }
}
