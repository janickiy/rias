<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{News};
use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Validator;
use URL;

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
            'preview' => 'required|min:6|max:255',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048|nullable',
            'slug' => 'required|unique:pages',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pic = $request->file('image');

        if (isset($pic)) {
            $destinationPath = public_path('/uploads/news/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();
            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);
        }

        News::create(array_merge(array_merge($request->all()), ['image' => $filename ?? null]));

        return redirect(URL::route('cp.news.index'))->with('success', 'Данные успешно добавлены');

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
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
            'preview' => 'required|min:6|max:255',
            'slug' => 'required|unique:pages,slug,' . $request->id,
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

        $pic = $request->file('image');

        if (isset($pic)) {

            $image = $request->pic;

            if ($image != null) {
                $dir = public_path("/uploads/news/$image");
                if (file_exists($dir)) {
                    @unlink($dir);
                }
            }

            $destinationPath = public_path('/uploads/news/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();

            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);

            $row->image = $filename;
        }

        $row->save();

        return redirect(URL::route('cp.news.index'))->with('success', 'Данные успешно обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        News::where('id', $request->id)->delete();
    }
}
