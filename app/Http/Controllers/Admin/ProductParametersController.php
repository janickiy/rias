<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductParameters;
use App\Http\Requests\Admin\ProductParameters\StoreRequest;
use App\Http\Requests\Admin\ProductParameters\EditRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ProductParametersController extends Controller
{

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $parameters = ProductParameters::where('product_id', $product_id)->get();

        if (!$parameters) abort(404);

        return view('cp.product_parameters.index', compact('parameters', 'product_id'))->with('title', 'Технические характеристики');
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        return view('cp.product_parameters.create_edit', compact('product_id'))->with('title', 'Добавление параметра');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        ProductParameters::create($request->all());

        return redirect()->route('cp.product_parameters.index', ['product_id' => $request->product_id])->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = ProductParameters::find($id);

        if (!$row) abort(404);

        $product_id = $row->product_id;

        return view('cp.product_parameters.create_edit', compact('row', 'product_id'))->with('title', 'Редактирование параметра');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductParameters::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->value = $request->input('value');
        $row->save();

        return redirect()->route('cp.product_parameters.index', ['product_id' =>  $row->product_id])->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        ProductParameters::where('id', $request->id)->delete();
    }
}
