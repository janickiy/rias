<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Catalog,News,Pages,FeedBack,Products};
use App\Events\{FeedbackMailEvent};
use Harimayco\Menu\Models\Menus;
use URL;
use Validator;
use stdClass;


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

        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();


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

        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        return view('frontend.index', compact(
            'page',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'seo_url_canonical',
            'top_menu',
            'bottom_menu')
        )->with('title', $title);
    }

    /**
     * @param string|null $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function news(string $slug = null)
    {
        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        if ($slug) {
            $news = News::where('slug', $slug)->first();

            if (!$news) abort(404);

            $title = $news->title;
            $meta_description = $news->meta_description ?? '';
            $meta_keywords = $news->meta_keywords ?? '';
            $meta_title = $news->meta_title ?? '';
            $seo_url_canonical = $news->seo_url_canonical ?? '';
        } else {
            $news = News::paginate(5);

            $title = 'Новости';
            $meta_description = '';
            $meta_keywords = '';
            $meta_title = '';
            $seo_url_canonical = '';
        }

        return view('frontend.news', compact(
            'news',
            'slug',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'seo_url_canonical',
            'top_menu',
            'bottom_menu'))->with('title', $title);
    }

    /**
     * @param string|null $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function catalog(string $slug = null)
    {
        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        if ($slug) {
            $catalog = Catalog::where('slug', $slug)->first();

            if (!$catalog) abort(404);

            $title = $catalog->title;
            $meta_description = $catalog->meta_description ?? '';
            $meta_keywords = $catalog->meta_keywords ?? '';
            $meta_title = $catalog->meta_title ?? '';
            $seo_url_canonical = $catalog->seo_url_canonical ?? '';
        } else {
            $catalog = Catalog::orderBy('name')->get();
            $title = 'Каталог';
            $meta_description = '';
            $meta_keywords = '';
            $meta_title = '';
            $seo_url_canonical = '';
        }

        return view('frontend.catalog', compact(
                'catalog',
                'slug',
                'meta_description',
                'meta_keywords',
                'meta_title',
                'seo_url_canonical',
                'top_menu',
                'bottom_menu')
        )->with('title', $title);
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

        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        return view('frontend.product', compact(
            'product',
            'slug',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'seo_url_canonical',
            'top_menu',
            'bottom_menu')
        )->with('title', $title);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function contact()
    {
        $meta_description = '';
        $meta_keywords = '';
        $meta_title = '';

        $menu1 = Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        return view('frontend.contact', compact('meta_description', 'meta_keywords', 'meta_title', 'top_menu', 'bottom_menu'))->with('title', 'Обратная связь');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendMsg(Request $request)
    {
        $rules = [
            'message' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'captcha' => 'required|captcha',
        ];

        $message = [
            'name.required' => 'Укажите Ваше имя!',
            'email.required' => 'Не указан Email!',
            'email.email' => 'Не верно указан Email!',
            'message.required' => 'Введите сообщение',
            'catalog_id.required' => 'Выберите раздел!',
            'captcha.required' => 'Не указан защитный код!',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Feedback::create(array_merge($request->all(), ['ip' => $request->ip()]));

        $data = new stdClass();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->message = $request->message;

        event(new FeedbackMailEvent($data));

        return redirect(URL::route('frontend.contact'))->with('success', 'Ваше сообщение успешно отправлено');
    }
}
