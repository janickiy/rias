<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StringHelper;
use App\Models\Images;
use Illuminate\Http\Request;
use App\Models\Photoalbums;
use Illuminate\Support\Facades\Validator;
use URL;
use Image;
use Storage;

class PhotoAlbumsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.photoalbums.index')->with('title', 'Новости');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('cp.photoalbums.create_edit')->with('title', 'Добавление фотоальбома');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Photoalbums::create($request->all());

        return redirect(URL::route('cp.photoalbums.index'))->with('success', 'Данные успешно добавлены');

    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = Photoalbums::find($id);

        if (!$row) abort(404);

        return view('cp.photoalbums.create_edit', compact('row'))->with('title', 'Редактирование фотоальбома');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = Photoalbums::find($request->id);

        if (!$row) abort(404);

        $row->title = $request->input('title');
        $row->save();

        return redirect(URL::route('cp.photoalbums.index'))->with('success', 'Данные успешно обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $photoalbum = Photoalbums::find($request->id);

        if ($photoalbum) {

            foreach ($photoalbum->photos as $photo) {

                if (Storage::disk('public')->exists('images/' . $photo->thumbnail) === true) Storage::disk('public')->delete('images/' . $photo->thumbnail);
                if (Storage::disk('public')->exists('images/' . $photo->origin) === true) Storage::disk('public')->delete('images/' . $photo->origin);

                $photo->delete();
            }

            $photoalbum->delete();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $photoalbum_id)
    {
        $photoalbum = Photoalbums::find($photoalbum_id);

        if (!$photoalbum) abort(404);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.photoalbums.show', compact('photoalbum', 'maxUploadFileSize'))->with('title', $photoalbum->title);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload(Request $request)
    {
        $rules = [
            'name' => 'required',
            'photoalbum_id' => 'required|integer|exists:photoalbums,id',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $extension = $request->file('image')->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $fileNameToStore = 'origin_' . $filename;
        $thumbnailFileNameToStore = 'thumbnail_' . $filename;

        if ($request->file('image')->storeAs('public/images', $fileNameToStore)) {
            $thumbnail = Image::make(Storage::path('/public/images/') . $fileNameToStore);
            $thumbnail->resize(null, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            if ($thumbnail->save(Storage::path('/public/images/') . $thumbnailFileNameToStore)) {
                Images::create(array_merge(array_merge($request->all()), [
                        'thumbnail' => $thumbnailFileNameToStore,
                        'origin' => $fileNameToStore
                    ]
                ));

                return redirect(URL::route('cp.photoalbums.show', ['photoalbum_id' => $request->photoalbum_id]))->with('success', 'Данные успешно добавлены');
            }
        }

        return redirect(URL::route('cp.photoalbums.show', ['photoalbum_id' => $request->photoalbum_id]))->with('error', 'Ошибка добавления фото');

    }
}
