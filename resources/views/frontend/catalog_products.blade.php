@extends('layouts.frontend')

@section('title', $title)

@section('description', $meta_description)

@section('keywords', $meta_keywords)

@section('seo_url_canonical', $seo_url_canonical)

@section('css')


@endsection

@section('content')

    <div class="model">
        <div class="container container--xl">
            <div class="model__content">
                <div class="model__main">
                    <div class="breadcrumbs model__breadcrumbs">
                        <div class="breadcrumbs__list">
                            <div class="breadcrumbs__item">
                                <a class="breadcrumbs__link" href="catalog.html">Оборудование</a>
                            </div>
                            <div class="breadcrumbs__item">
                                <span class="breadcrumbs__current">Циркониевые анализаторы кислорода Lonhot</span>
                            </div>
                        </div>
                    </div>
                    <a class="btn-back model__mobile-back" href="catalog.html">Назад</a>
                    <div class="title1 model__title">
                        <h1>&emsp;<b>Газоанализаторы LONHOT</b> предназначены для непрерывного измерения объёмной доли кислорода
                            и/или оксида углерода (II) и продуктов неполного сгорания в пересчете на монооксид углерода (СОе) в
                            дымовых и технологических газах, для технологического контроля и мониторинга в системах контроля
                            выбросов</h1>
                    </div>
                    <div class="model__info">
                        <img class="model__img" src="img/arrows.svg" alt="">
                        <div class="text-content model__text">
                            <p><b>Основные области применения:</b> автоматические системы контроля промышленных выбросов (АСКПВ/
                                CEMS), контроль и оптимизация процессов горения в энергетических и отопительных котлах, в печах для
                                отжига, обжига, в печах нефтеперерабатывающих и нефтехимических, металлургических, для сжигания мусора
                                и отходов, и прочих производств в различных отраслях промышленности, в том числе, в опасных по
                                воспламенению газовоздушной среды зонах (пожаровзрывоопасных)</p>
                        </div>
                    </div>
                </div>
                <div class="products model__products">
                    <div class="products__content">
                        <div class="products__info">
                            <div class="breadcrumbs">
                                <div class="breadcrumbs__list">
                                    <div class="breadcrumbs__item">
                                        <span class="breadcrumbs__current">Продукция Lonhot</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="products__list">
                            <div class="product-card product-card--sm-column products__item products__item--col-4">
                                <a class="product-card__img" href="catalog-card.html">
                                    <picture>
                                        <source srcset="img/products/4.webp" type="image/webp">
                                        <source srcset="img/products/4.png" type="image/png">
                                        <img src="img/products/4.png" width="390" height="175" alt="">
                                    </picture>
                                </a>
                                <div class="product-card__info">
                                    <h3 class="product-card__name">
                                        <a href="catalog-card.html">
                                            <span>LONOСМ6000</span>
                                        </a>
                                    </h3>
                                    <p class="text product-card__text">Газоанализатор кислорода</p>
                                </div>
                            </div>
                            <div class="product-card product-card--sm-column products__item products__item--col-4">
                                <a class="product-card__img" href="catalog-card.html">
                                    <picture>
                                        <source srcset="img/products/5.webp" type="image/webp">
                                        <source srcset="img/products/5.png" type="image/png">
                                        <img src="img/products/5.png" width="320" height="250" alt="">
                                    </picture>
                                </a>
                                <div class="product-card__info">
                                    <h3 class="product-card__name">
                                        <a href="catalog-card.html">
                                            <span>LONOXT3000</span>
                                        </a>
                                    </h3>
                                    <p class="text product-card__text">Циркониевый газоанализатор кислорода</p>
                                </div>
                            </div>
                            <div class="product-card product-card--sm-column products__item products__item--col-4">
                                <a class="product-card__img" href="catalog-card.html">
                                    <picture>
                                        <source srcset="img/products/2.webp" type="image/webp">
                                        <source srcset="img/products/2.png" type="image/png">
                                        <img src="img/products/2.png" width="390" height="206" alt="">
                                    </picture>
                                </a>
                                <div class="product-card__info">
                                    <h3 class="product-card__name">
                                        <a href="catalog-card.html">
                                            <span>LONHOT SERVOCC6100</span>
                                        </a>
                                    </h3>
                                    <p class="text product-card__text">Газоанализаторы кислорода и/или оксида углерода (II) и продуктов
                                        неполного сгорания, в пересчёте на монооксид углерода (COe)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="callback">
        <div class="container container--xl">
            <div class="callback__content">
                <div class="callback__main">
                    <div class="breadcrumbs">
                        <div class="breadcrumbs__list">
                            <div class="breadcrumbs__item">
                                <span class="breadcrumbs__current">Заявка на расчет проекта</span>
                            </div>
                        </div>
                    </div>
                    <div class="callback__info">
                        <div class="title1 callback__title">
                            <h2>Нужна помощь с подбором? Оформите заявку</h2>
                        </div>
                        <a class="btn btn--black callback__btn" href="{{ URL::route('frontend.application') }}">Оформить заявку на расчет проекта</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')


@endsection
