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

    <div class="col-sm-12" style="margin-top:10px">{{ Breadcrumbs::render('page', $page) }}</div>

    <div class="col-sm-12">

        <h1>{{ $meta_title ? $meta_title : $page->title }}</h1>

        @if(isset($arr) && $arr)
            <div class="row">

                <div class="col-sm-12 bg-white rounded box-shadow">

                    <table class="table table-responsive borderless">
                        @for ($i = 0; $i < $number; $i++)
                            <tr>
                                @for ($j = 0; $j < \App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++)
                                    <td style="vertical-align: top; width: {{ 100/\App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER') }}%">
                                        @if(isset($arr[$i][$j][1]) && isset($arr[$i][$j][0]) && isset($arr[$i][$j][3]))
                                            <table class="table table-responsive borderless">
                                                <tr>
                                                    <td style="width: 80px; padding:6px; vertical-align: top;">
                                                        <img style="border: 0; border-width: 0;" width="50px"
                                                             src="{{ isset($arr[$i][$j][2]) && $arr[$i][$j][2] ? url('uploads/catalog/' . $arr[$i][$j][2]) : url('/img/folder.jpg') }}">
                                                    </td>
                                                    <td style="padding:6px">
                                                        <strong><a href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">{{ $arr[$i][$j][0] }}</a></strong>
                                                        @if($arr[$i][$j][1] > 0)
                                                            <br>
                                                            <div class="subcat">

                                                                {!! \App\Models\Catalog::ShowSubCat($arr[$i][$j][1]) !!}

                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </table>

                </div>
            </div>
        @endif

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
