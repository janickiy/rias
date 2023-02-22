<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="canonical" href="@yield('seo_url_canonical')"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- #CSS Links -->
    <!-- Basic Styles -->
{!! Html::style('css/normalize.css?v=3') !!}
{!! Html::style('css/fonts.css?v=3') !!}
{!! Html::style('css/main.css?v=3') !!}
{!! Html::style('css/media.css?v=3') !!}

@yield('css')

<!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <script type="text/javascript">
        var SITE_URL = "{{ url('/') }}";
    </script>

</head>

<body class="body">

<header class="header header--index">
    <div class="header__top">
        <div class="container header__container">
            <div class="header__top-links">
                <a class="link header__top-link" href="#">
                    <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 11.3333V9.91667L4.60417 5.66667L0 1.41667V0H8.5V2.125H3.38229L7.18958 5.66667L3.38229 9.20833H8.5V11.3333H0Z"/>
                    </svg>
                    Конвертер ед. изм.
                </a>
                <div class="lang dropdown header__lang">
                    <button class="link lang__toggle dropdown__btn">RU</button>
                    <div class="lang__dropdown dropdown__main">
                        <div class="lang__btns">
                            <a class="btn lang__btn" href="index.html">RU</a>
                            <a class="btn lang__btn" href="index.html">EN</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="container header__container">
            <div class="header__bottom-content">
                <a class="logo header__logo" href="{{ url('/') }}">
                    <img class="logo__img" src="{{ url('img/logo.png') }}" alt="">
                    <span class="logo__text">РИАС</span>
                </a>
                <div class="header__mobile-links">
                    <a class="link header__mobile-link" href="#">Оборудование</a>
                    <a class="link header__mobile-link" href="#">Контакты</a>
                </div>
                <nav class="menu header__menu">
                    <div class="menu__content">
                        <div class="menu__header">
                            <div class="menu__title">Меню сайта</div>
                            <button class="menu__close">
                                <span class="menu__lines menu__lines--close"></span>
                            </button>
                        </div>
                        <ul class="menu__list">

                            @if($top_menu)

                                @foreach( $top_menu as $menu )
                                    <li class="menu__item"><a class="menu__link"
                                                              href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                                @endforeach

                            @endif

                            <li class="menu__item menu__item--mobile">
                                <a class="menu__link" href="#">
                                    <img src="{{ url('img/icons/converter-black.svg') }}" alt="">
                                    Конвертер ед. изм.
                                </a>
                            </li>

                        </ul>
                        <div class="menu__lang-box">
                            <span>Язык на сайте:</span>
                            <div class="lang lang--menu dropdown">
                                <button class="link lang__toggle dropdown__btn">RU</button>
                                <div class="lang__dropdown dropdown__main">
                                    <div class="lang__btns">
                                        <a class="btn btn--black lang__btn" href="index.html">RU</a>
                                        <a class="btn btn--black lang__btn" href="index.html">EN</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn--black menu__btn">Заявка на расчет проекта</button>
                    </div>
                    <button class="menu__open">
                        <span class="menu__lines"></span>
                    </button>
                </nav>
                <button class="btn header__btn">Заявка на расчет проекта</button>
            </div>
        </div>
    </div>
</header>

@yield('content')

<footer class="footer">
    <div class="container">
        <div class="footer__content">
            <div class="footer__copyright">© {{ date("Y") }} Все права
                защищены. {{ SettingsHelper::getSetting('SITE_NAME') }}</div>
            <ul class="footer__menu">
                <li class="footer__menu-item">
                    <a class="link footer__menu-link"
                       href="mailto:{{ SettingsHelper::getSetting('EMAIL') }}">{{ SettingsHelper::getSetting('EMAIL') }}</a>
                </li>
                <li class="footer__menu-item">
                    <a class="link footer__menu-link"
                       href="tel:{{ SettingsHelper::getSetting('PHONE') }}">тел. {{ SettingsHelper::getSetting('PHONE') }}</a>
                </li>
            </ul>
        </div>
    </div>
</footer>


<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src="{!! asset('js/libs/jquery-3.2.1.min.js') !!}"><\/script>');
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="{!! asset('js/libs/jquery-ui.min.js') !!}"><\/script>');
    }
</script>

{!! Html::script('js/main.js?=3') !!}


@yield('js')

</body>
</html>
