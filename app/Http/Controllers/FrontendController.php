<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Pages,FeedBack};
use App\Events\{FeedbackMailEvent};
use URL;
use Validator;
use stdClass;

class FrontendController
{
    public function index()
    {
        $page = Pages::where('main', 1)->first();

        if (!$page) abort(404);

        $title = $page->title ?? 'Главная страница';
        $meta_description = $page->meta_description ?? '';
        $meta_keywords = $page->meta_keywords ?? '';
        $meta_title = $page->meta_title ?? '';

        $menu1 = \Harimayco\Menu\Models\Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = \Harimayco\Menu\Models\Menus::where('name', 'bottom')->with('items')->first();
        $bottom_menu = $menu2->items->toArray();

        return view('frontend.index', compact('page', 'meta_description', 'meta_keywords', 'meta_title', 'top_menu', 'bottom_menu'))->with('title', $title);
    }

    public function page($slug)
    {
        $page = Pages::where('slug', $slug)->first();

        if (!$page) abort(404);

        $title = $page->title ?? 'Главная страница';
        $meta_description = $page->meta_description ?? '';
        $meta_keywords = $page->meta_keywords ?? '';
        $meta_title = $page->meta_title ?? '';

        $menu = \Harimayco\Menu\Models\Menus::where('id', 1)->with('items')->first();

        $top_menu = $menu->items->toArray();

        return view('frontend.index', compact('page', 'meta_description', 'meta_keywords', 'meta_title', 'top_menu'))->with('title', $title);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function contact()
    {
        $meta_description = '';
        $meta_keywords = '';
        $meta_title = '';

        $menu1 = \Harimayco\Menu\Models\Menus::where('name', 'top')->with('items')->first();
        $top_menu = $menu1->items->toArray();

        $menu2 = \Harimayco\Menu\Models\Menus::where('name', 'bottom')->with('items')->first();
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
