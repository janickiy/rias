<?php

namespace App\Http\Controllers;

use App\Helpers\SettingsHelper;
use Illuminate\Http\{
    Response,
    JsonResponse
};
use App\Http\Requests\Frontend\{
    SendApplicationRequest,
    ConvertRequest,
};
use App\Models\{Catalog, Gaz, News, Pages, Products, Seo};
use Harimayco\Menu\Models\Menus;
use App\Mail\Notification;
use URL;
use Validator;
use File;
use Mail;


class FrontendController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $page = Pages::where('main', 1)->first();

        if (!$page) abort(404);

        $title = $page->title ?? 'Главная';
        $meta_description = $page->meta_description ?? '';
        $meta_keywords = $page->meta_keywords ?? '';
        $meta_title = $page->meta_title ?? '';
        $seo_url_canonical = $page->seo_url_canonical ?? '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        $products = Products::inRandomOrder()->limit(3)->get();

        return view('frontend.index', compact(
                'products',
                'page',
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu')
        )->with('title', $title);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function about()
    {
        $title = 'О компании';
        $meta_description = '';
        $meta_keywords = '';
        $meta_title = '';
        $seo_url_canonical = '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.about', compact(
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu')
        )->with('title', $title);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function page(string $slug)
    {
        $page = Pages::where('slug', $slug)->first();

        if (!$page) abort(404);

        $title = $page->title ?? 'Главная страница';
        $meta_description = $page->meta_description ?? '';
        $meta_keywords = $page->meta_keywords ?? '';
        $meta_title = $page->meta_title ?? '';
        $seo_url_canonical = $page->seo_url_canonical ?? '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.index', compact(
                'page',
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu')
        )->with('title', $title);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function news()
    {
        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        $news = News::paginate(5);


        $seo = Seo::where('type', 'frontend.news')->first();

        $title = $seo->h1 ?? 'Новости компании РИАС';
        $meta_description = $seo->description ?? '';
        $meta_keywords = $seo->keyword ?? '';
        $meta_title = $seo->title ?? '';
        $seo_url_canonical = $seo->url_canonical ?? '';


        return view('frontend.news', compact(
            'news',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'seo_url_canonical',
            'top_menu'))->with('title', $title);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function openNews(string $slug)
    {
        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        $news = News::where('slug', $slug)->first();

        if (!$news) abort(404);

        $title = $news->title;
        $meta_description = $news->meta_description ?? '';
        $meta_keywords = $news->meta_keywords ?? '';
        $meta_title = $news->meta_title ?? '';
        $seo_url_canonical = $news->seo_url_canonical ?? '';

        return view('frontend.open_news', compact(
            'news',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'seo_url_canonical',
            'top_menu'))->with('title', $title);

    }

    /**
     * @param string|null $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function catalog(string $slug = null)
    {
        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        if ($slug) {
            $catalog = Catalog::where('slug', $slug)->first();

            if (!$catalog) abort(404);

            $products = Products::where('catalog_id', $catalog->id)->paginate(6);

            $title = $catalog->name;
            $meta_description = $catalog->meta_description;
            $meta_keywords = $catalog->meta_keywords;
            $meta_title = $catalog->meta_title;
            $seo_url_canonical = $catalog->seo_url_canonical;

            return view('frontend.catalog_products', compact(
                    'catalog',
                    'products',
                    'meta_description',
                    'meta_keywords',
                    'meta_title',
                    'seo_url_canonical',
                    'top_menu')
            )->with('title', $title);
        } else {
            $seo = Seo::where('type', 'frontend.catalog')->first();

            $title = $seo->h1 ?? '	Наше оборудование';
            $meta_description = $seo->description ?? '';
            $meta_keywords = $seo->keyword ?? '';
            $meta_title = $seo->title ?? '';
            $seo_url_canonical = $seo->url_canonical ?? '';

            $catalogs = Catalog::orderBy('name')->get();

            return view('frontend.catalog', compact(
                    'catalogs',
                    'meta_description',
                    'meta_keywords',
                    'meta_title',
                    'seo_url_canonical',
                    'top_menu')
            )->with('title', $title);
        }
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function product(string $slug)
    {
        $product = Products::where('slug', $slug)->first();

        if (!$product) abort(404);

        $title = $product->title;
        $meta_description = $product->meta_description ?? '';
        $meta_keywords = $product->meta_keywords ?? '';
        $meta_title = $product->meta_title ?? '';
        $seo_url_canonical = $product->seo_url_canonical ?? '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.product', compact(
                'product',
                'slug',
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu')
        )->with('title', $title);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function contact()
    {
        $seo = Seo::where('type', 'frontend.contact')->first();

        $title = $seo->h1 ?? 'Конвертер единиц измерения концентрации';
        $meta_description = $seo->description ?? '';
        $meta_keywords = $seo->keyword ?? '';
        $meta_title = $seo->title ?? '';
        $seo_url_canonical = $seo->url_canonical ?? '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.contact', compact(
                'meta_description',
                'meta_keywords',
                'meta_title',
                'top_menu',
                'seo_url_canonical',
                'title',
            )
        )->with('title', 'Обратная связь');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function converter()
    {

        $seo = Seo::where('type', 'frontend.converter')->first();

        $title = $seo->h1 ?? 'Конвертер единиц измерения концентрации';
        $meta_description = $seo->description ?? '';
        $meta_keywords = $seo->keyword ?? '';
        $meta_title = $seo->title ?? '';
        $seo_url_canonical = $seo->url_canonical ?? '';

        $options = Gaz::getOption();

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.converter', compact(
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu',
                'options'
            )
        )->with('title', $title);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function application()
    {

        $seo = Seo::where('type', 'frontend.application')->first();

        $title = $seo->h1 ?? 'Заявка на расчет проекта';
        $meta_description = $seo->description ?? '';
        $meta_keywords = $seo->keyword ?? '';
        $meta_title = $seo->title ?? '';
        $seo_url_canonical = $seo->url_canonical ?? '';

        $menu = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu->items->toArray();

        return view('frontend.application', compact(
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu'
            )
        )->with('title', $title);
    }

    /**
     * @param SendApplicationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendApplication(SendApplicationRequest $request)
    {

        $path = public_path('uploads');
        $attachment = $request->file('attachment');

        $name = time() . '.' . $attachment->getClientOriginalExtension();;

        // create folder
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $attachment->move($path, $name);
        $filename = $path . '/' . $name;

        try {

            Mail::to(explode(",", SettingsHelper::getSetting('EMAIL_NOTIFY')))->send(new Notification($filename));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Спасибо, что обратились в компанию РИАС!<br>Ваш файл отправлен.<br>Менеджер свяжется с Вами в ближайшее время.');
    }

    /**
     * @param ConvertRequest $request
     * @return JsonResponse
     */
    public function gazСonvert(ConvertRequest $request): JsonResponse
    {
        try {
            $gaz = Gaz::find($request->gaz_id);

            if (!$gaz) return response()->json(['errors' => true, 'message' => 'not found'], Response::HTTP_NOT_FOUND);

            $data = $gaz->convert($request->convertType, $request->value);

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json(['errors' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
