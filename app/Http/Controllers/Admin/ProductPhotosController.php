<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    ProductPhotos,
    Products
};
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\ProductPhotos\StoreRequest;
use App\Http\Requests\Admin\ProductPhotos\EditRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Validator;
use Storage;
use Image;

class ProductPhotosController extends Controller
{
    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $row = Products::find($product_id);

        if (!$row) abort(404);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.product_photos.index', compact('row', 'maxUploadFileSize'))->with('title', 'Фото оборудования: ' . $row->title);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function upload(StoreRequest $request): RedirectResponse
    {
        $extension = $request->file('image')->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $fileNameToStore = 'origin_' . $filename;
        $thumbnailFileNameToStore = 'thumbnail_' . $filename;

        if ($request->file('image')->storeAs('public/images', $fileNameToStore)) {
            $thumbnail = Image::make(Storage::path('/public/images/') . $fileNameToStore);
            $thumbnail->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            if ($thumbnail->save(Storage::path('/public/images/') . $thumbnailFileNameToStore)) {
                ProductPhotos::create(array_merge(array_merge($request->all()), [
                        'thumbnail' => $thumbnailFileNameToStore,
                        'origin' => $fileNameToStore
                    ]
                ));

                return redirect()->route('cp.product_photos.index', ['product_id' => $request->product_id])->with('success', 'Данные успешно добавлены');
            }
        }

        return redirect()->route('cp.product_photos.index', ['product_id' => $request->product_id])->with('error', 'Ошибка добавления фото');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = ProductPhotos::find($id);

        if (!$row) abort(404);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.product_photos.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование фото: ' . $row->product->title );
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductPhotos::find($request->id);

        if (!$row) abort(404);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists('images/' . $row->thumbnail) === true) Storage::disk('public')->delete('images/' . $row->thumbnail);
            if (Storage::disk('public')->exists('images/' . $row->origin) === true) Storage::disk('public')->delete('images/' . $row->origin);

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $fileNameToStore = 'origin_' . $filename;
            $thumbnailFileNameToStore = 'thumbnail_' . $filename;

            if ($request->file('image')->storeAs('public/images', $fileNameToStore)) {
                $img = Image::make(Storage::path('/public/images/') . $fileNameToStore);
                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });

                if ($img->save(Storage::path('/public/images/') . $thumbnailFileNameToStore)) {
                    $row->thumbnail = $thumbnailFileNameToStore;
                    $row->origin = $fileNameToStore;
                }
            }
        }

        $row->title = $request->input('title');
        $row->alt = $request->input('alt');
        $row->save();

        return redirect()->route('cp.product_photos.index', ['product_id' => $row->product_id])->with('success', 'Данные успешно обновлены');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $row = ProductPhotos::find($request->id);

        if ($row) {
            if (Storage::disk('public')->exists('images/' . $row->thumbnail) === true) Storage::disk('public')->delete('images/' . $row->thumbnail);
            if (Storage::disk('public')->exists('images/' . $row->origin) === true) Storage::disk('public')->delete('images/' . $row->origin);

            $row->delete();
        }
    }
}
