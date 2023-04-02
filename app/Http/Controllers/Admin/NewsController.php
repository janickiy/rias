<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catalog;
use Illuminate\Http\Request;
use App\Models\News;
use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Validator;
use URL;
use Storage;
use Image;

class NewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cp.news.index')->with('title', 'Новости');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.news.create_edit', compact('maxUploadFileSize'))->with('title', 'Добавление новости');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|min:6|max:200',
            'text' => 'required',
            'preview' => 'required|min:6|max:400',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048|nullable',
            'slug' => 'required|unique:news',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            if ($request->file('image')->storeAs('public/news', $filename)) {
                $img = Image::make(Storage::path('/public/news/') . $filename);
                $img->resize(null, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(Storage::path('/public/news/') . $filename);
            }
        }

        News::create(array_merge(array_merge($request->all()), [
            'image' => $filename ?? null,
        ]));

        return redirect(URL::route('cp.news.index'))->with('success', 'Данные успешно добавлены');

    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = News::find($id);

        if (!$row) abort(404);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.news.create_edit', compact('row', 'maxUploadFileSize'))->with('title', 'Редактирование новости');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required|min:6|max:200',
            'text' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048|nullable',
            'preview' => 'required|min:6|max:400',
            'slug' => 'required|unique:news,slug,' . $request->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = News::find($request->id);

        if (!$row) abort(404);

        $row->title = $request->input('title');
        $row->text = $request->input('text');
        $row->preview = $request->input('preview');
        $row->meta_title = $request->input('meta_title');
        $row->meta_description = $request->input('meta_description');
        $row->meta_keywords = $request->input('meta_keywords');
        $row->slug = $request->input('slug');
        $row->seo_h1 = $request->input('seo_h1');
        $row->seo_url_canonical = $request->input('seo_url_canonical');

        if ($request->hasFile('image')) {

            $image = $request->pic;

            if ($image != null) {
                if (Storage::disk('public')->exists('news/' . $row->image) === true) Storage::disk('public')->delete('news/' . $row->image);
            }

            if ($request->hasFile('image')) {

                if (Storage::disk('public')->exists('news/' . $row->image) === true) Storage::disk('public')->delete('news/' . $row->image);

                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = time() . '.' . $extension;

                if ($request->file('image')->storeAs('public/news', $filename)) {
                    $img = Image::make(Storage::path('/public/news/') . $filename);
                    $img->resize(null, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    if ($img->save(Storage::path('/public/news/') . $filename)) $row->image = $filename;
                }
            }
        }

        $row->save();

        return redirect(URL::route('cp.news.index'))->with('success', 'Данные успешно обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $news = News::find($request->id);

        if ($news) {
            if (Storage::disk('public')->exists('news/' . $news->image) === true) Storage::disk('public')->delete('news/' . $news->image);
        }

        $news->delete();
    }
}
