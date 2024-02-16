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

                        {!! Form::open(['url' => isset($row) ? URL::route('cp.gaz_group.update') : URL::route('cp.gaz_group.store'), 'method' => isset($row) ? 'put' : 'post', 'id' => 'smart-form']) !!}

                        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

                        <div class="smart-form">
                            <header>
                                *-Обязательные поля
                            </header>

                            <fieldset>

                                <section>

                                    {!! Form::label('name', 'Название*', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('name', old('name', isset($row) ? $row->name : null), ['class' => 'form-control']) !!}

                                    </label>

                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif

                                </section>

                                <section>

                                    {!! Form::label('name_ru', 'Название RU*', ['class' => 'label']) !!}

                                    <label class="input">

                                        {!! Form::text('name_ru', old('name_ru', isset($row) ? $row->name_ru : null), ['class' => 'form-control']) !!}

                                    </label>

                                    @if ($errors->has('name_ru'))
                                        <p class="text-danger">{{ $errors->first('name_ru') }}</p>
                                    @endif

                                </section>


                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary button-apply">
                                    {{ isset($row) ? 'Изменить' : 'Добавить' }}
                                </button>
                                <a class="btn btn-default" href="{{ URL::route('cp.gaz_group.index') }}">
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

    {!! Html::script('/admin/js/plugin/ckeditor/ckeditor.js') !!}

    <script>
        $(document).ready(function () {

            CKEDITOR.replace( 'text', {
                extraAllowedContent: 'img[title]',
                height: 380,
                startupFocus: true,
                filebrowserUploadUrl: '/upload.php',
                on: {
                    instanceReady: function() {
                        this.dataProcessor.htmlFilter.addRules( {
                            elements: {
                                img: function( el ) {
                                    el.attributes.title = el.attributes.alt;
                                }
                            }
                        });
                    }
                }
            });

            CKEDITOR.config.allowedContent = true;
            CKEDITOR.config.removePlugins = 'spellchecker, about, save, newpage, print, templates, scayt, flash, pagebreak, smiley,preview,find';
            CKEDITOR.config.extraAllowedContent = 'img[title]';

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
