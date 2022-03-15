@extends('admin.main')
@section('content')

    <div class="container">

        <h1>Создание типа контента</h1>
        <form action="{{route('type-content.store')}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                <div class="col-md-6">
                    <input autofocus type="text" name="name" placeholder="Введите название" id="name"
                           class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="api_url" class="col-md-4 col-form-label text-md-right">API URL</label>
                <div class="col-md-6">
                    <input type="text" name="api_url" placeholder="Automatic..." id="api_url" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                <div class="col-md-6">
                    <textarea name="description" placeholder="Введите описание" id="description"
                              class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                <div class="col-md-6">
                    <input type="datetime-local" name="active_from" placeholder="Введите активен с" id="active_from"
                           class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                <div class="col-md-6">
                    <input type="datetime-local" name="active_after" placeholder="Введите активен по" id="active_after"
                           class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="icon" class="col-md-4 col-form-label text-md-right">Иконка</label>
                <div class="col-md-6">
                    <select id="icon" type="text" class="form-control" name="icon"></select>
                </div>
            </div>

            <button id="btn1" type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
    <script>
        //todo: убрать перед загрузкой:)
        // let focus = 0;
        // $('#name').focusout(function () {
        //     focus++;
        //     if (focus >= 5) {
        //         $('#btn1').text('Ты серьезно? Уже ' + focus + ' исправлений...');
        //     }
        // });

        function urlLit(w, v) {
            var tr = 'a b v g d e ["zh","j"] z i y k l m n o p r s t u f h c ch sh ["shh","shch"] ~ y ~ e yu ya ~ ["jo","e"]'.split(' ');
            var ww = '';
            w = w.toLowerCase();
            for (i = 0; i < w.length; ++i) {
                cc = w.charCodeAt(i);
                ch = (cc >= 1072 ? tr[cc - 1072] : w[i]);
                if (ch.length < 3) ww += ch; else ww += eval(ch)[v];
            }
            return (ww.replace(/[^a-zA-Z0-9\-]/g, '-').replace(/[-]{2,}/gim, '-').replace(/^\-+/g, '').replace(/\-+$/g, ''));
        }

        $(document).ready(function () {
            $('#name').bind('change keyup input click', function () {
                $('#api_url').val(urlLit($('#name').val(), 0))
            });
            {{--$.ajaxSetup({--}}
            {{--    headers: {--}}
            {{--        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--    }--}}
            {{--});--}}
            {{--$.ajax({--}}
            {{--    url: '{{route('type-content.get-icons')}}',--}}
            {{--    type: 'POST',--}}
            {{--    data: {--}}
            {{--        "_token": "{{ csrf_token() }}"--}}
            {{--    },--}}
            {{--    success: function (response) {--}}
            {{--        console.log(response);--}}
            {{--    },error:function(){--}}
            {{--        console.log("err");--}}
            {{--    }--}}
            {{--});--}}
             let icons_array = ['fa-edit', 'fa-eye', 'fa-plus-circle'];

            {{--let icons_array = '<?php echo json_encode($icons_array);?>';--}}
            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                let $state = $('<span class="fa ' + state.id.toLowerCase() + ' fa-lg" aria-hidden="true"></span><span>' + state.id + '</span>');
                return $state;
            };
            $("#icon").select2({
                templateSelection: formatState,
                templateResult: formatState,
                allowHtml: true,
                theme: 'classic',
                data: icons_array,
            });
            $('#icon').on('change', function () {
                $('.fa').hover(function () {
                    $(this).addClass('fa-spin');
                }, function () {
                    $(this).removeClass('fa-spin');
                });
            });
            $('#icon').trigger('change');
        });
    </script>
@endsection


