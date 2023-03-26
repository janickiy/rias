@extends('cp.app')

@section('title', $title)

@section('css')


@endsection

@section('content')

    <!-- START ROW -->
    <div class="row">

        <!-- NEW COL START -->
        <article class="col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-custombutton="false">
                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"
                -->

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        {!! Form::open(['url' => isset($row) ? URL::route('cp.product_videos.update') : URL::route('cp.product_videos.store'), 'method' => isset($row) ? 'put' : 'post', 'id' => 'smart-form']) !!}

                        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

                        {!! Form::hidden('product_id', $product_id) !!}

                        <div class="smart-form">

                            <header>
                                *-Обязательные поля
                            </header>

                            <fieldset>

                                <section>

                                    {!! Form::label('video', 'video ID*', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('video', old('video', isset($row) ? $row->video : null), ['class' => 'form-control', 'id' => 'title']) !!}

                                    </label>

                                    @if ($errors->has('video'))
                                        <p class="text-danger">{{ $errors->first('video') }}</p>
                                    @endif

                                </section>

                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary button-apply">
                                    {{ isset($row) ? 'Изменить' : 'Добавить' }}
                                </button>
                                <a class="btn btn-default" href="{{ URL::route('cp.product_videos.index', ['product_id' => $product_id]) }}">
                                    Назад
                                </a>
                            </footer>

                        </div>

                        {!! Form::close() !!}

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- END COL -->

    </div>

    <!-- END ROW -->

@endsection

@section('js')


@endsection
