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

    <div class="col-sm-12" style="margin-top:10px">{{ Breadcrumbs::render('contact') }}</div>

    <div class="col-sm-12">

        <h1>Контакты</h1>

        @if (!session('success'))

            <p>*-обязательные поля</p>

            {!! Form::open(['url' =>  URL::route('frontend.send_msg'), 'method' => 'post', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('name', 'Ваше имя*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('name', old('name', null), ['class' => 'form-control', 'placeholder'=>'Ваше имя']) !!}

                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('email', 'Email*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', old('email', null), ['class' => 'form-control', 'placeholder'=>'Email']) !!}

                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('message', 'Сообщение*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('message', old('message', null), ['placeholder' =>'Ваше сообщение','class' => 'form-control', 'rows' => 3]) !!}

                    @if ($errors->has('message'))
                        <span class="text-danger">{{ $errors->first('message') }}</span>
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('captcha', 'Защитный код*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">

                    {!! Form::text('captcha', null, ['class' => 'form-control', 'placeholder'=>'Название', 'id' => 'captcha']) !!}

                    <br>
                    @captcha
                    <br>

                    @if ($errors->has('captcha'))
                        <span class="text-danger">{{ $errors->first('captcha') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit( 'Отправить', ['class'=>'btn btn-success']) !!}
                </div>
            </div>

            {!! Form::close() !!}

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



@endsection
