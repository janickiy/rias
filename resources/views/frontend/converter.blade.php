@extends('layouts.frontend')

@section('title', $title)

@section('description', $meta_description)

@section('keywords', $meta_keywords)

@section('seo_url_canonical', $seo_url_canonical)

@section('css')


@endsection

@section('content')

    <section class="converter">
        <div class="container container--xl">
            <div class="converter__content">

                <div class="converter__header">
                    <div class="breadcrumbs">
                        <div class="breadcrumbs__list">
                            <div class="breadcrumbs__item">
                                <span class="breadcrumbs__current">Конвертер единиц измерения концентрации</span>
                            </div>
                        </div>
                    </div>
                    <div class="title1 converter__title">
                        <h1>позволяет проводить <b>пересчёт концентрации выбранного газа</b> из указанного значения
                            единицы концентрации
                            в три других значения, в том числе и % НКПР (нижний концентрационный предел распространения
                            пламени) для
                            горючих газов.</h1>
                    </div>
                </div>

                <form class="convert-form converter__form" autocomplete="off" method="GET">
                    <div class="convert-form__main">
                        <div class="convert-form__item">
                            <span class="convert-form__label">Наименование вещества</span>
                            <div class="select convert-form__select">
                                <select class="select-field">
                                    <option value="">Выберите вещество...</option>
                                    <option value="1">Азот (N2)</option>
                                    <option value="2">Азот (N2)</option>
                                    <option value="3">Азот (N2)</option>
                                    <option value="4">Азот (N2)</option>
                                    <option value="5">Азот (N2)</option>
                                    <option value="6">Азот (N2)</option>
                                    <option value="7">Азот (N2)</option>
                                    <option value="8">Азот (N2)</option>
                                    <option value="9">Азот (N2)</option>
                                    <option value="10">Азот (N2)</option>
                                    <option value="11">Азот (N2)</option>
                                    <option value="12">Азот (N2)</option>
                                </select>
                            </div>
                        </div>
                        <div class="convert-form__item">
                            <span class="convert-form__label">единица измерения</span>
                            <div class="convert-form__checkboxes">
                                <label class="checkbox convert-form__checkbox">
                                    <input class="checkbox__input" type="radio" name="unit" checked>
                                    <span class="checkbox__switch"></span>
                                    <span class="checkbox__text">ppm</span>
                                </label>
                                <label class="checkbox convert-form__checkbox">
                                    <input class="checkbox__input" type="radio" name="unit">
                                    <span class="checkbox__switch"></span>
                                    <span class="checkbox__text">мг/м3</span>
                                </label>
                                <label class="checkbox convert-form__checkbox">
                                    <input class="checkbox__input" type="radio" name="unit">
                                    <span class="checkbox__switch"></span>
                                    <span class="checkbox__text">% об. д.</span>
                                </label>
                                <label class="checkbox convert-form__checkbox">
                                    <input class="checkbox__input" type="radio" name="unit">
                                    <span class="checkbox__switch"></span>
                                    <span class="checkbox__text">% НКПР</span>
                                </label>
                            </div>
                        </div>
                        <div class="convert-form__item">
                            <span class="convert-form__label">Значение</span>
                            <input class="input input--num convert-form__input" type="text" placeholder="0">
                        </div>
                        <div class="convert-form__footer">
                            <button class="btn btn--border convert-form__footer-btn">Конвертировать</button>
                            <button class="btn btn--white btn--border convert-form__footer-btn">Сбросить</button>
                        </div>
                    </div>
                </form>

                <div class="convert-result converter__result">
                    <div class="breadcrumbs convert-result__breadcrumbs">
                        <div class="breadcrumbs__list">
                            <div class="breadcrumbs__item">
                                <span class="breadcrumbs__current">Результаты конвертации</span>
                            </div>
                        </div>
                    </div>
                    <div class="convert-result__main">
                        <div class="convert-result__item">
                            <span class="convert-result__name">ppm</span>
                            <span class="convert-result__value">-</span>
                        </div>
                        <div class="convert-result__item">
                            <span class="convert-result__name">мг/м3</span>
                            <span class="convert-result__value">-</span>
                        </div>
                        <div class="convert-result__item">
                            <span class="convert-result__name">% об. д.</span>
                            <span class="convert-result__value">-</span>
                        </div>
                        <div class="convert-result__item">
                            <span class="convert-result__name">% НКПР</span>
                            <span class="convert-result__value">-</span>
                        </div>
                    </div>
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
                        <a class="btn btn--black callback__btn" href="{{ URL::route('frontend.application') }}">Оформить заявку на расчет
                            проекта</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('js')


@endsection
