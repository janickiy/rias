@extends('layouts.frontend')

@section('title', $title)

@section('description', $meta_description)

@section('keywords', $meta_keywords)

@section('css')


@endsection

@section('content')

    <div class="product">
        <div class="container container--xl">
            <div class="product__content">

                <div class="product__header">
                    <div class="breadcrumbs product__breadcrumbs">
                        <div class="breadcrumbs__list">
                            <div class="breadcrumbs__item">
                                <a class="breadcrumbs__link" href="catalog.html">Оборудование</a>
                            </div>
                            <div class="breadcrumbs__item">
                                <a class="breadcrumbs__link" href="catalog-lonhot.html">Циркониевые анализаторы кислорода Lonhot</a>
                            </div>
                            <div class="breadcrumbs__item">
                                <span class="breadcrumbs__current">LONOСМ6000</span>
                            </div>
                        </div>
                    </div>
                    <a class="btn-back product__mobile-back" href="catalog-lonhot.html">Назад</a>
                    <div class="title1 product__title">
                        <h1>{{ $product->title }}</h1>
                    </div>
                </div>

                <div class="product__main">
                    <div class="product__sliders">
                        <div class="product__nav-wrapper">
                            <div class="product__nav-slider swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide product__nav-slide">
                                        <picture>
                                            <source srcset="img/products/3.webp" type="image/webp">
                                            <source srcset="img/products/3.png" type="image/png">
                                            <img src="img/products/3.png" alt="" width="48" height="52">
                                        </picture>
                                    </div>
                                    <div class="swiper-slide product__nav-slide">
                                        <picture>
                                            <source srcset="img/products/3-2.webp" type="image/webp">
                                            <source srcset="img/products/3-2.png" type="image/png">
                                            <img src="img/products/3-2.png" alt="" width="48" height="52">
                                        </picture>
                                    </div>
                                    <div class="swiper-slide product__nav-slide">
                                        <picture>
                                            <source srcset="img/products/3-2.webp" type="image/webp">
                                            <source srcset="img/products/3-2.png" type="image/png">
                                            <img src="img/products/3-2.png" alt="" width="48" height="52">
                                        </picture>
                                    </div>
                                    <div class="swiper-slide product__nav-slide product__nav-slide--video">
                                        <picture>
                                            <source srcset="img/products/3-video-preview.webp" type="image/webp">
                                            <source srcset="img/products/3-video-preview.png" type="image/png">
                                            <img src="img/products/3-video-preview.png" alt="" width="62" height="62">
                                        </picture>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product__big-slider swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide product__big-slide">
                                    <picture>
                                        <source srcset="img/products/3.webp" type="image/webp">
                                        <source srcset="img/products/3.png" type="image/png">
                                        <img src="img/products/3.png" alt="" width="186" height="218">
                                    </picture>
                                </div>
                                <div class="swiper-slide product__big-slide">
                                    <picture>
                                        <source srcset="img/products/3-2.webp" type="image/webp">
                                        <source srcset="img/products/3-2.png" type="image/png">
                                        <img src="img/products/3-2.png" alt="" width="232" height="104">
                                    </picture>
                                </div>
                                <div class="swiper-slide product__big-slide">
                                    <picture>
                                        <source srcset="img/products/3-2.webp" type="image/webp">
                                        <source srcset="img/products/3-2.png" type="image/png">
                                        <img src="img/products/3-2.png" alt="" width="232" height="104">
                                    </picture>
                                </div>
                                <div class="swiper-slide product__big-slide product__big-slide--video">
                                    <div class="product__big-slide-video" data-src="https://www.youtube.com/embed/Gx543KbuGFY"></div>
                                </div>
                            </div>
                            <button class="product__slider-prev"></button>
                            <button class="product__slider-next"></button>
                        </div>
                    </div>
                    <div class="text-content text-content--big product__excerpt">
                        <p>{{ $product->description }}</p>
                        <a class="link-more link-more--down product__excerpt-more" href="#product-tabs">Полное описание</a>
                    </div>
                </div>

                <div class="tabs product__tabs" id="product-tabs">
                    <div class="tabs__btns">
                        <button class="tabs__btn tabs__btn--active">Описание</button>
                        <button class="tabs__btn">Модели</button>
                        <button class="tabs__btn">Документация</button>
                    </div>
                    <div class="tabs__list">
                        <div class="tabs__item tabs__item--active">
                            <div class="product__column product__column--img">
                                <div class="text-content text-content--big product__column-text">
                                    <p>{{ $product->description }}</p>
                                </div>
                                @if($product->thumbnail)
                                <div class="product__column-img">
                                    <picture>
                                        <source srcset="{{ $product->getThumbnailUrl() }}" type="{{ StringHelper::get_mime_type($product->origin) }}">
                                        <img src="{{ $product->getThumbnailUrl() }}" alt="" width="705" height="348">
                                    </picture>
                                </div>
@endif

                            </div>
                            <div class="product__column">
                                <div class="text-content text-content--big product__column-text">
                                    <p>Датчик на основе циркониевой ячейки изготавливается с применением технологии пористого
                                        платинового покрытия и фиксируется в корпусе зонда с помощью уникальной технологии платиновой
                                        сварки. По сравнению с циркониевыми датчиками других производителей, изготовленных с помощью
                                        «клеевой» технологии, наш датчик обладает высокой механической прочностью. <br>
                                        В процессе эксплуатации циркониевых датчиков, изготовленных с помощью «клеевой» технологии,
                                        высока вероятность растрескивания или утечек, возникающих вследствие динамических температур
                                        дымовых газов или механических воздействий. Конструкция циркониевых датчиков LONHOT гарантирует
                                        идеальную герметичность и чрезвычайно высокую точность измерений.</p>
                                    <h2>Технические особенности</h2>
                                    <ul>
                                        <li>Пластина из диоксида циркония покрыта пористым платиновым слоем</li>
                                        <li>Возможность замены компонентов анализатора (циркониевая ячейка, нагревательный элемент,
                                            термопара и другие) в полевых условиях</li>
                                        <li>Уникальный процесс сварки платины</li>
                                        <li>В сигнальной линии циркониевого элемента используется контактная сетка из платиновой проволоки
                                        </li>
                                        <li>Датчики и фланцы различных размеров</li>
                                        <li>Дополнительная функция автоматической калибровки/ продувки</li>
                                    </ul>
                                    <h2>Преимущества использования</h2>
                                    <ul>
                                        <li>Устойчивость к агрессивным средам в дымовых и технологических газах,</li>
                                        <li>увеличение срока службы</li>
                                        <li>Гарантированная герметичность циркониевой ячейки, устойчивость к высоким температурам,
                                            устойчивость к вибрации</li>
                                        <li>Высокая герметичность и механическая прочность датчика</li>
                                        <li>Недорогое обслуживание</li>
                                        <li>Широкий спектр применения</li>
                                        <li>Частичная автоматизация процесса</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="product__models">
                                <div class="product-model product__models-item">
                                    <picture>
                                        <source srcset="img/products/3.webp" type="image/webp">
                                        <source srcset="img/products/3.png" type="image/png">
                                        <img class="product-model__img" src="img/products/3.jpg" alt="" width="186" height="218">
                                    </picture>
                                    <div class="text-content text-content--big product-model__main">
                                        <h2>Электронный блок</h2>
                                        <ul>
                                            <li>Корпус: литой алюминиевый корпус с дисплеем</li>
                                            <li>Маркировка взрывозащиты</li>
                                            <li>Уровень защиты: IP66</li>
                                            <li>Дисплей: графический ЖК-дисплей с разрешением 192*64, меню на китайском и английском языках
                                            </li>
                                            <li>Температура окр. среды: от -40оС до +55оС</li>
                                            <li>Относительная влажность: ≤ 97% (+55оС)</li>
                                            <li>Электрическое подключение: 100/ 240 В, переменный ток 50/ 60 Гц</li>
                                            <li>Диапазон измерения: 0-25 %, программируемый</li>
                                            <li>Точность измерения: ±0,1% O2</li>
                                            <li>Точность поддержания температуры: ± 1℃</li>
                                            <li>Выходной сигнал: 4-20 мА</li>
                                            <li>Протокол HART, с дополнительной опцией удаленного управления. Profibus Fieldbus.</li>
                                            <li>Максимальная нагрузка: ≤ 500 Ом</li>
                                            <li>Размеры: d=230 мм, h=143 мм</li>
                                            <li>Вес: около 6 кг</li>
                                            <li>Опции: автоматическая калибровка (продувка) устройства</li>
                                        </ul>
                                    </div>
                                    <a class="link-more product-model__more" href="#">Матрица заказа электронного блока</a>
                                </div>
                                <div class="product-model product__models-item">
                                    <picture>
                                        <source srcset="img/products/3-2.webp" type="image/webp">
                                        <source srcset="img/products/3-2.png" type="image/png">
                                        <img class="product-model__img" src="img/products/3-2.jpg" alt="" width="240" height="108">
                                    </picture>
                                    <div class="text-content text-content--big product-model__main">
                                        <h2>Аналитический блок</h2>
                                        <ul>
                                            <li>Материал зонда: опционально 316L, 904L</li>
                                            <li>Маркировка взрывозащиты</li>
                                            <li>Уровень защиты: IP66</li>
                                            <li>Температура дымовых газов: ≤ +700℃ (возможно использование до +1500⁰С, с применением</li>
                                            <li>байпасной высокотемпературной трубки для охлаждения дымовых газов)</li>
                                            <li>Давление дымовых газов: от -10 до +10 кПа</li>
                                            <li>Расход дымовых газов: 0…50 м/с</li>
                                            <li>Температура окр. среды: от -40℃ до +80℃</li>
                                            <li>Время отклика: ≤ 0,5 с</li>
                                            <li>Время установления показаний (T90): ≤ 5 с</li>
                                            <li>Длина вставки: TES300 A - 320 мм,</li>
                                            <li>TES300 B - 535 мм,</li>
                                            <li>TES300 F - 795 мм,</li>
                                            <li>TES300 C - 1080 мм,</li>
                                            <li>TES300 D - 1530 мм,</li>
                                            <li>TES300 E - 1915 мм</li>
                                            <li>Точность измерения: ±1% диапазона измерения или ±0,1% показаний O2</li>
                                        </ul>
                                    </div>
                                    <a class="link-more product-model__more" href="#">Матрица заказа аналитического блока</a>
                                </div>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="product__docs">
                                <a class="link-doc product__doc" href="#">
                                    <div class="link-doc__info">
                                        ЛТД LONHOT LONOCM6000 r08.docx
                                        <span class="text link-doc__text">Подробное описание оборудования, матрицы заказа, схемы и
												рисунки</span>
                                    </div>
                                </a>
                                <a class="link-doc product__doc" href="#">
                                    <div class="link-doc__info">
                                        ЛТД LONHOT LONOCM6000 r08.docx
                                        <span class="text link-doc__text">Подробное описание оборудования, матрицы заказа, схемы и
												рисунки</span>
                                    </div>
                                </a>
                                <a class="link-doc product__doc" href="#">
                                    <div class="link-doc__info">
                                        ЛТД LONHOT LONOCM6000 r08.docx
                                        <span class="text link-doc__text">Подробное описание оборудования, матрицы заказа, схемы и
												рисунки</span>
                                    </div>
                                </a>
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
