<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\ConvertRequest;
use App\Http\Requests\Frontend\SendApplicationRequest;
use App\Models\Catalog;
use App\Models\Gaz;
use App\Models\News;
use App\Models\Pages;
use App\Models\Products;
use App\Services\ApplicationService;
use App\Services\FrontendMenuService;
use App\Services\FrontendSeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function __construct(
        private readonly FrontendMenuService $menuService,
        private readonly FrontendSeoService  $seoService,
        private readonly ApplicationService  $applicationService,
    )
    {
    }

    public function index(): View
    {
        $page = Pages::query()->where('main', 1)->firstOrFail();

        return view('frontend.index', [
            'products' => Products::query()->inRandomOrder()->limit(3)->get(),
            'page' => $page,
            'meta_description' => $page->meta_description ?? '',
            'meta_keywords' => $page->meta_keywords ?? '',
            'meta_title' => $page->meta_title ?? '',
            'seo_url_canonical' => $page->seo_url_canonical ?? '',
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $page->title ?? 'Главная',
        ]);
    }

    /**
     * @return View
     */
    public function about(): View
    {
        $seo = $this->seoService->getByType('frontend.about', 'О компании');

        return view('frontend.about', [
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $seo['title'],
        ]);
    }

    /**
     * @param string $slug
     * @return View
     */
    public function page(string $slug): View
    {
        $page = Pages::query()->where('slug', $slug)->firstOrFail();
        $title = $page->title ?? 'Главная страница';

        return view('frontend.index', [
            'page' => $page,
            'meta_description' => $page->meta_description ?? '',
            'meta_keywords' => $page->meta_keywords ?? '',
            'meta_title' => $page->meta_title ?? '',
            'h1' => $page->h1 ?? $title,
            'seo_url_canonical' => $page->seo_url_canonical ?? '',
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $title,
        ]);
    }

    /**
     * @return View
     */
    public function news(): View
    {
        $seo = $this->seoService->getByType('frontend.news', 'Новости компании РИАС');

        return view('frontend.news', [
            'news' => News::query()->paginate(5),
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $seo['title'],
        ]);
    }

    /**
     * @param string $slug
     * @return View
     */
    public function openNews(string $slug): View
    {
        $news = News::query()->where('slug', $slug)->firstOrFail();

        return view('frontend.open_news', [
            'news' => $news,
            'meta_description' => $news->meta_description ?? '',
            'meta_keywords' => $news->meta_keywords ?? '',
            'meta_title' => $news->meta_title ?? '',
            'seo_url_canonical' => $news->seo_url_canonical ?? '',
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $news->title,
        ]);
    }

    /**
     * @param string|null $slug
     * @return View
     */
    public function catalog(?string $slug = null): View
    {
        if ($slug !== null) {
            $catalog = Catalog::query()->where('slug', $slug)->firstOrFail();

            return view('frontend.catalog_products', [
                'catalog' => $catalog,
                'products' => Products::query()
                    ->where('catalog_id', $catalog->id)
                    ->paginate(6),
                'meta_description' => $catalog->meta_description ?? '',
                'meta_keywords' => $catalog->meta_keywords ?? '',
                'meta_title' => $catalog->meta_title ?? '',
                'seo_url_canonical' => $catalog->seo_url_canonical ?? '',
                'top_menu' => $this->menuService->getTopMenu(),
                'title' => $catalog->name,
            ]);
        }

        $seo = $this->seoService->getByType('frontend.catalog', 'Наше оборудование');

        return view('frontend.catalog', [
            'catalogs' => Catalog::query()->orderBy('name')->get(),
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => 'Наше оборудование',
        ]);
    }

    /**
     * @param string $slug
     * @return View
     */
    public function product(string $slug): View
    {
        $product = Products::query()->where('slug', $slug)->firstOrFail();
        $title = $product->title;

        return view('frontend.product', [
            'product' => $product,
            'slug' => $slug,
            'meta_description' => $product->meta_description ?? '',
            'meta_keywords' => $product->meta_keywords ?? '',
            'meta_title' => $product->meta_title ?? '',
            'h1' => $product->h1 ?? $title,
            'seo_url_canonical' => $product->seo_url_canonical ?? '',
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $title,
        ]);
    }

    /**
     * @return View
     */
    public function contact(): View
    {
        $seo = $this->seoService->getByType('frontend.contact', 'Обратная связь');

        return view('frontend.contact', [
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'top_menu' => $this->menuService->getTopMenu(),
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'title' => $seo['title'],
        ]);
    }

    /**
     * @return View
     */
    public function converter(): View
    {
        $seo = $this->seoService->getByType('frontend.converter', 'Конвертер единиц измерения концентрации');

        return view('frontend.converter', [
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'top_menu' => $this->menuService->getTopMenu(),
            'options' => Gaz::getOption(),
            'title' => $seo['title'],
        ]);
    }

    /**
     * @return View
     */
    public function application(): View
    {
        $seo = $this->seoService->getByType('frontend.application', 'Заявка на расчет проекта');

        return view('frontend.application', [
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'meta_title' => $seo['meta_title'],
            'h1' => $seo['h1'],
            'seo_url_canonical' => $seo['seo_url_canonical'],
            'top_menu' => $this->menuService->getTopMenu(),
            'title' => $seo['title'],
        ]);
    }

    /**
     * @param SendApplicationRequest $request
     * @return RedirectResponse
     */
    public function sendApplication(SendApplicationRequest $request): RedirectResponse
    {
        try {
            $this->applicationService->send($request->file('attachment'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->back()
            ->with('success', 'Спасибо, что обратились в компанию РИАС!<br>Ваш файл отправлен.<br>Менеджер свяжется с Вами в ближайшее время.');
    }

    /**
     * @param ConvertRequest $request
     * @return JsonResponse
     */
    public function gazConvert(ConvertRequest $request): JsonResponse
    {
        try {
            $gaz = Gaz::find($request->integer('gaz_id'));

            if ($gaz === null) {
                return response()->json(
                    ['errors' => true, 'message' => 'not found'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $data = $gaz->convert(
                $request->input('convertType'),
                $request->input('value')
            );

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(
                ['errors' => true, 'message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
