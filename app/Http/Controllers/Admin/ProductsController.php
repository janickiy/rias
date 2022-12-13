<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Catalog, Products};
use App\Helpers\StringHelper;
use URL;
use Validator;
use Image;

class ProductsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.products.index')->with('title', 'Продукция');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $options = Catalog::getOption();

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.products.create_edit', compact('options', 'maxUploadFileSize'))->with('title', 'Добавление продукции');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:products',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048|nullable',
            'catalog_id' => 'integer|required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())  return back()->withErrors($validator)->withInput();

        $pic = $request->file('image');

        if (isset($pic)) {
            $destinationPath = public_path('/uploads/products/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();
            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);
        }

        Products::create(array_merge(array_merge($request->all()), ['image' => $filename ?? null]));

        return redirect(URL::route('cp.products.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = Products::where('id', $id)->first();

        if (!$row) abort(404);

        $options = Catalog::getOption();

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.products.create_edit', compact('row', 'options', 'maxUploadFileSize'))->with('title', 'Редактирование продукции');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:products,url,' . $request->id,
            'image' => 'image|mimes:jpeg,jpg,png|max:2048|nullable',
            'catalog_id' => 'integer|required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())  return back()->withErrors($validator)->withInput();

        $product = Products::find($request->id);

        if (!$product) abort(404);

        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->keywords = $request->input('keywords');
        $product->catalog_id = $request->catalog_id;
        $product->meta_title = $request->input('meta_title');
        $product->meta_description = $request->input('meta_description');
        $product->meta_keywords = $request->input('meta_keywords');
        $product->slug = $request->input('slug');

        $pic = $request->file('image');

        if (isset($pic)) {

            $image = $request->pic;

            if ($image != null) {
                $dir = public_path("/uploads/products/$image");
                if (file_exists($dir)) {
                    @unlink($dir);
                }
            }

            $destinationPath = public_path('/uploads/products/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();

            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);

            $product->image = $filename;
        }

        $product->save();

        return redirect(URL::route('cp.products.index'))->with('success', 'Данные обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        Products::find($request->id)->delete();
    }
}
