@extends('layouts.frontend')

@section('title', $title)

@section('description', $meta_description)

@section('keywords', $meta_keywords)

@section('css')

    <style>

        nav {
            margin-top: 20px;
            box-shadow: 5px 4px 5px #000;
        }

    </style>

@endsection

@section('content')


    @if($top_menu)
        <div class="body-wrap">
            <div class="container">
                <nav class="navbar navbar-inverse" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">

                            <a class="navbar-brand" href="/">Главная </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                @foreach($top_menu as $menu)

                                    @if( $menu['child'] )

                                        <li class="dropdown">

                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown">{{ $menu['label'] }} <b
                                                    class="caret"></b></a>

                                            <ul class="dropdown-menu">
                                                @foreach( $menu['child'] as $child )
                                                    <li><a href="{{ $child['link'] }}">{{ $child['label'] }}</a></li>
                                                @endforeach
                                            </ul>

                                        </li>
                                    @else
                                        <li class="active"><a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                                    @endif
                                @endforeach
                            </ul>

                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </div>
        </div>
    @endif

    <div class="col-sm-12" style="margin-top:10px">{{ Breadcrumbs::render('product', $product) }}</div>


    <div class="col-sm-12">

        <h1>{{ $product->seo_h1 ?? $title }}</h1>

       @if($product->image) <img src="{{ url('uploads/products/' . $product->image)  }}" alt="Вернуться на главную страницу"> @endif

        {!! $product->description !!}

    </div>

    @if($bottom_menu)
        <div class="col-12 col-md-8">
            <ul class="list-inline">
                @foreach( $bottom_menu as $menu )
                    <li class="list-inline-item"><a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection

@section('js')

    <script>
        $('ul.nav li.dropdown').hover(function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
        }, function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
        });

    </script>

@endsection
