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

                        {!! Form::open(['url' => isset($row) ? URL::route('cp.news.update') : URL::route('cp.news.store'), 'method' => isset($row) ? 'put' : 'post', 'id' => 'tmplForm']) !!}

                        <div class="smart-form">

                            {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

                            <header>
                                *-Обязательные поля
                            </header>

                            <fieldset>

                                <section>

                                    {!! Form::label('title', 'Название*', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('title', old('title', isset($row) ? $row->title : null), ['class' => 'form-control', 'id' => 'title']) !!}

                                    </label>

                                    @if ($errors->has('title'))
                                        <p class="text-danger">{{ $errors->first('title') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('preview', 'Краткое описание*', ['class' => 'label']) !!}

                                    <label class="textarea textarea-resizable">

                                        {!! Form::textarea('preview', old('preview', isset($row) ? $row->preview : null), ['rows' => "3", 'class' => 'custom-scroll']) !!}

                                    </label>

                                    @if ($errors->has('preview'))
                                        <p class="text-danger">{{ $errors->first('preview') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('text', 'Содержание*', ['class' => 'label']) !!}

                                    <label class="textarea textarea-resizable">

                                        {!! Form::textarea('text', old('text', isset($row) ? $row->text : null), ['rows' => "6", 'class' => 'custom-scroll', 'id'=> 'text']) !!}

                                    </label>

                                    @if ($errors->has('text'))
                                        <p class="text-danger">{{ $errors->first('text') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('slug', 'ЧПУ*', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('slug', old('slug', isset($row) ? $row->slug : null), ['class' => 'form-control', 'id' => 'slug']) !!}

                                    </label>

                                    @if ($errors->has('slug'))
                                        <p class="text-danger">{{ $errors->first('slug') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('meta_title', 'Seo title', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('meta_title', old('meta_title', isset($row) ? $row->meta_title : null), ['class' => 'form-control']) !!}

                                    </label>

                                    @if ($errors->has('meta_title'))
                                        <p class="text-danger">{{ $errors->first('meta_title') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('meta_title', 'Meta description', ['class' => 'label']) !!}

                                    <label class="textarea textarea-resizable">

                                        {!! Form::textarea('meta_description', old('meta_description', isset($row) ? $row->meta_description : null), ['rows' => "3", 'class' => 'custom-scroll']) !!}

                                    </label>

                                    @if ($errors->has('meta_description'))
                                        <p class="text-danger">{{ $errors->first('meta_description') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('meta_keywords', 'Meta keywordsn', ['class' => 'label']) !!}

                                    <label class="textarea textarea-resizable">

                                        {!! Form::textarea('meta_keywords', old('meta_keywords', isset($row) ? $row->meta_keywords : null), ['rows' => "3", 'class' => 'custom-scroll']) !!}

                                    </label>

                                    @if ($errors->has('meta_keywords'))
                                        <p class="text-danger">{{ $errors->first('meta_keywords') }}</p>
                                    @endif

                                </section>

                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary button-apply">
                                    {{ isset($row) ? 'Изменить' : 'Добавить' }}
                                </button>
                                <a class="btn btn-default" href="{{ URL::route('cp.news.index') }}">
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

    </div></div>

    <!-- END ROW -->

@endsection

@section('js')

    {!! Html::script('/admin/js/plugin/ckeditor/ckeditor.js') !!}

    <script>
        $(document).ready(function () {

            CKEDITOR.replace('text', {height: '380px', startupFocus: true});
            CKEDITOR.config.allowedContent = true;

            $("#title").on("change keyup input click", function () {
                if (this.value.length >= 2) {
                    var title = this.value;
                    var request = $.ajax({
                        url: '{!! URL::route('cp.ajax.action') !!}',
                        method: "POST",
                        data: {
                            action: "get_news_slug",
                            title: title
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: "json"
                    });
                    request.done(function (data) {
                        if (data.slug != null && data.slug != '') {
                            $("#slug").val(data.slug);
                        }
                    });
                }
                console.log(html);
            });

        });
    </script>


@endsection
