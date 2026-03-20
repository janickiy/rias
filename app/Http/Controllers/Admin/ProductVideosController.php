<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\ProductVideoData;
use App\Helpers\VideoHelper;
use App\Http\Requests\Admin\ProductVideos\EditRequest;
use App\Http\Requests\Admin\ProductVideos\StoreRequest;
use App\Http\Requests\Admin\ProductVideos\DeleteRequest;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVideoRepository;
use App\Services\VideoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductVideosController extends Controller
{
    public function __construct(
        private readonly ProductVideoRepository $videoRepository,
        private readonly ProductRepository $productRepository,
        private readonly VideoService $videoService,
    ) {
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function index(int $product_id): View
    {
        $product = $this->productRepository->findOrFail($product_id);
        $videos = $this->videoRepository->getByProductId($product_id);

        return view('cp.product_videos.index', [
            'videos' => $videos,
            'product_id' => $product_id,
            'title' => 'Список видео: ' . $product->title,
        ]);
    }

    /**
     * @param int $product_id
     * @return View
     */
    public function create(int $product_id): View
    {
        $product = $this->productRepository->findOrFail($product_id);

        return view('cp.product_videos.create_edit', [
            'product_id' => $product_id,
            'title' => 'Добавление видео: ' . $product->title,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $this->prepareDataForStore($request);

        $this->videoRepository->create(
            ProductVideoData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_videos.index', [
                'product_id' => $request->integer('product_id'),
            ])
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->videoRepository->findOrFail($id);
        $row->video = VideoHelper::getVideoLink($row->provider, $row->video);

        return view('cp.product_videos.create_edit', [
            'row' => $row,
            'product_id' => $row->product_id,
            'title' => 'Редактирование списка видео: ' . $row->product->title,
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->videoRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $row->product_id);

        $this->videoRepository->update(
            $row,
            ProductVideoData::fromArray($data)
        );

        return redirect()
            ->route('cp.product_videos.index', [
                'product_id' => $row->product_id,
            ])
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param DeleteRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteRequest $request): RedirectResponse
    {
        $row = $this->videoRepository->find($request->integer('id'));

        if ($row !== null) {
            $productId = $row->product_id;

            $this->videoRepository->delete($row);

            return redirect()
                ->route('cp.product_videos.index', [
                    'product_id' => $productId,
                ])
                ->with('success', 'Информация успешно удалена');
        }

        return redirect()
            ->back()
            ->with('error', 'Видео не найдено');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();
        $parsedVideo = $this->videoService->parse($data['video']);

        $data['provider'] = $parsedVideo['provider'];
        $data['video'] = $parsedVideo['video'];

        return $data;
    }

    /**
     * @param EditRequest $request
     * @param int $productId
     * @return array
     */
    private function prepareDataForUpdate(EditRequest $request, int $productId): array
    {
        $data = $request->validated();
        $parsedVideo = $this->videoService->parse($data['video']);

        $data['provider'] = $parsedVideo['provider'];
        $data['video'] = $parsedVideo['video'];
        $data['product_id'] = $productId;

        return $data;
    }
}
