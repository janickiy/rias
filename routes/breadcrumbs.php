<?php

Breadcrumbs::register('main', function ($breadcrumbs) {

    $breadcrumbs->push('Главная', url('/'));

});


Breadcrumbs::register('page', function ($breadcrumbs, $page) {

    $breadcrumbs->parent('main');

    $breadcrumbs->push($page->title, route('frontend.page', ['slug' => $page->slug]));

});
