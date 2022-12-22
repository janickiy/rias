<?php

Breadcrumbs::register('main', function ($breadcrumbs) {
    $breadcrumbs->push('Главная', url('/'));
});


Breadcrumbs::register('contact', function ($breadcrumbs) {
    $breadcrumbs->parent('main');
    $breadcrumbs->push('Контакты', URL::route('frontend.contact'));
});

Breadcrumbs::register('news', function ($breadcrumbs, $news) {
    $breadcrumbs->parent('main');

    if (isset($news->title))
        $breadcrumbs->push($news->title, URL::route('frontend.news', ['slug' => $news->slug]));
    else
        $breadcrumbs->push('Новости', URL::route('frontend.news'));
});


Breadcrumbs::register('page', function ($breadcrumbs, $page) {
    $breadcrumbs->parent('main');
    $breadcrumbs->push($page->title, route('frontend.pages', ['slug' => $page->slug]));
});

Breadcrumbs::register('catalog', function ($breadcrumbs) {
    $breadcrumbs->parent('main');
    $breadcrumbs->push('Продукция', URL::route('frontend.catalog'));
});

Breadcrumbs::register('product', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('catalog');
    $breadcrumbs->push($product->catalog->name, route('frontend.catalog', ['slug' => $product->catalog->slug]));
    $breadcrumbs->push($product->title, route('frontend.product', ['slug' => $product->slug]));
});
