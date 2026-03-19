<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductVideoData;
use App\Helpers\VideoHelper;
use App\Http\Requests\Admin\ProductVideos\EditRequest;
use App\Http\Requests\Admin\ProductVideos\StoreRequest;
use App\Models\Products;
use App\Models\ProductVideos;
use App\Repositories\ProductVideoRepository;
use App\Services\VideoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductVideosController extends Controller
{
    public function __construct(
        private readonly ProductVideoRepository $videoRepository,
        private readonly VideoService $videoService,
    ) {
    }

    public function index(int $product_id): View
    {
        $videos = ProductVideos::where('product_id', $product_id)->get();
        $product = Products::findOrFail($product_id);

        return view('cp.product_videos.index', compact('videos', 'product_id'))->with('title', 'Список видео: ' . $product->title);
    }

    public function create(int $product_id): View
    {
        $product = Products::findOrFail($product_id);

        return view('cp.product_videos.create_edit', compact('product_id'))->with('title', 'Добавление видео: ' . $product->title);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $video = $this->videoService->parse($validated['video']);
        $validated['provider'] = $video['provider'];
        $validated['video'] = $video['video'];

        $this->videoRepository->create(ProductVideoData::fromArray($validated));

        return redirect()->route('cp.product_videos.index', ['product_id' => $request->integer('product_id')])->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id): View
    {
        $row = ProductVideos::findOrFail($id);
        $row->video = VideoHelper::getVideoLink($row->provider, $row->video);
        $product_id = $row->product_id;

        return view('cp.product_videos.create_edit', compact('row', 'product_id'))->with('title', 'Редактирование списка видео: ' . $row->product->title);
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = ProductVideos::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $video = $this->videoService->parse($validated['video']);
        $validated['provider'] = $video['provider'];
        $validated['video'] = $video['video'];
        $validated['product_id'] = $row->product_id;

        $this->videoRepository->update($row, ProductVideoData::fromArray($validated));

        return redirect()->route('cp.product_videos.index', ['product_id' => $row->product_id])->with('success', 'Данные обновлены');
    }

    public function destroy(Request $request): void
    {
        $row = ProductVideos::find($request->id);

        if ($row) {
            $this->videoRepository->delete($row);
        }
    }
}
