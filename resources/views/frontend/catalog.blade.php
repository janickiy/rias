@extends('layouts.frontend')

@section('title', $title)

@section('description', $meta_description)

@section('keywords', $meta_keywords)

@section('seo_url_canonical', $seo_url_canonical)

@section('css')


@endsection

@section('content')

    <section class="products products--pt-0">
        <div class="container container--xl">
            <div class="products__content">
                <div class="title1 main__title">
                    <h1>{{ $catalog->seo_h1 ?? $title }}</h1>
                </div>
                <div class="products__models-list">

                    @foreach($products as $product)

                    <div class="product-models products__models">
                        <div class="product-models__main">
                            <div class="title2 product-models__title">
                                <h2>{{ $product->title }}</h2>
                            </div>
                        </div>
                        <a class="link-more product-models__more" href="catalog-lonhot.html">Все модели</a>
                        <div class="product-models__img">
                            <picture>
                                <source srcset="{{ url('') }}img/products/1.png" type="image/png">
                                <img src="{{ url('') }}img/products/1.png" width="234" height="218" alt="">
                            </picture>
                        </div>
                        <div class="product-models__items">
                            <a class="text product-models__item" href="catalog-card.html">Анализатор следовых концентраций кислорода TP‑96 O2</a>
                            <a class="text product-models__item" href="catalog-card.html">Переносные газовые анализаторы SPGAS</a>
                            <a class="text product-models__item" href="catalog-card.html">Общепромышленные газовые анализаторы S‑200</a>
                            <a class="text product-models__item" href="catalog-card.html">Взрывозащищенные газовые анализаторы S‑200 ATEX</a>
                        </div>
                    </div>

                    @endforeach

                </div>
            </div>
        </div>
    </section>

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
                        <a class="btn btn--black callback__btn" href="application.html">Оформить заявку на расчет проекта</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('js')



@endsection
