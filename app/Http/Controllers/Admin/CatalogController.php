<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use App\Models\{Catalog};
use App\Http\Requests\Admin\Catalog\StoreRequest;
use App\Http\Requests\Admin\Catalog\EditRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Storage;
use Image;

class CatalogController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.catalog.index')->with('title', 'Категории');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('maxUploadFileSize'))->with('title', 'Добавление категории');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $fileNameToStore = $filename;

            if ($request->file('image')->storeAs('public/catalog', $fileNameToStore)) {
                $img = Image::make(Storage::path('/public/catalog/') . $fileNameToStore);
                $img->resize(null, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(Storage::path('/public/catalog/') . $fileNameToStore);
            }
        }

        Catalog::create(array_merge(array_merge($request->all()), [
            'image' => $fileNameToStore ?? null,
        ]));

        return redirect()->route('cp.catalog.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = Catalog::find($id);

        if (!$row) abort(404);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование категории');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = Catalog::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->description = $request->input('description');
        $row->scope = $request->input('scope');
        $row->slug = $request->input('slug');
        $row->meta_title = $request->input('meta_title');
        $row->meta_description = $request->input('meta_description');
        $row->meta_keywords = $request->input('meta_keywords');
        $row->seo_h1 = $request->input('seo_h1');
        $row->seo_url_canonical = $request->input('seo_url_canonical');

        if ($request->hasFile('image')) {

            $image = $request->pic;

            if ($image != null) {
                if (Storage::disk('public')->exists('catalog/' . $row->image) === true) Storage::disk('public')->delete('catalog/' . $row->image);
            }

            if ($request->hasFile('image')) {

                if (Storage::disk('public')->exists('catalog/' . $row->image) === true) Storage::disk('public')->delete('catalog/' . $row->image);

                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = time() . '.' . $extension;

                if ($request->file('image')->storeAs('public/catalog', $filename)) {
                    $img = Image::make(Storage::path('/public/catalog/') . $filename);
                    $img->resize(null, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    if ($img->save(Storage::path('/public/catalog/') . $filename)) $row->image = $filename;

                }
            }
        }

        $row->image_title = $request->input('image_title');
        $row->image_alt = $request->input('image_alt');
        $row->save();

        return redirect()->route('cp.catalog.index')->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        Catalog::find($request->id)->remove();
    }
}
