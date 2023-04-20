<?php

namespace App\Http\Controllers\Admin;

use App\Models\Products;
use App\Models\ProductVideos;
use Illuminate\Http\Request;
use App\Helpers\VideoHelper;
use Validator;
use URL;

class ProductVideosController extends Controller
{

    /**
     * @param int $product_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(int $product_id)
    {
        $videos = ProductVideos::where('product_id', $product_id)->get();

        if (!$videos) abort(404);

        $product = Products::find($product_id);

        if (!$product) abort(404);

        return view('cp.product_videos.index', compact('videos', 'product_id'))->with('title', 'Список видео: ' . $product->title);
    }

    /**
     * @param int $product_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(int $product_id)
    {
        $product = Products::find($product_id);

        if (!$product) abort(404);

        return view('cp.product_videos.create_edit', compact('product_id'))->with('title', 'Добавление видео: ' . $product->title);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'video' => 'required',
            'product_id' => 'required|integer|exists:products,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $video = VideoHelper::detectVideoId($request->video);

        ProductVideos::create(array_merge($request->all(),['provider' => $video['provider'], 'video' => $video['video']]));

        return redirect(URL::route('cp.product_videos.index', ['product_id' => $request->product_id]))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = ProductVideos::find($id);

        if (!$row) abort(404);

        $row->video = VideoHelper::getVideoLink($row->provider, $row->video);

        $product_id = $row->product_id;

        return view('cp.product_videos.create_edit', compact('row', 'product_id'))->with('title', 'Редактирование списка видео: ' . $row->product->title);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'video' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $row = ProductVideos::find($request->id);

        if (!$row) abort(404);

        $video = VideoHelper::detectVideoId($request->input('video'));

        $row->video = $video['video'];
        $row->provider = $video['provider'];
        $row->save();

        return redirect(URL::route('cp.product_videos.index', ['product_id' =>  $row->product_id]))->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        ProductVideos::where('id', $request->id)->delete();
    }
}
