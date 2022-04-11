<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <div class="text-center"><h3>Контентная модель</h3></div>

        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-button col-2">
                    <button id="hideshow" class="btn btn-primary"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                </div>
                <div class="search-form col-8">
                    <div class="form">
                        <form action="{{route('type-content.store')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование типа" id="name" class="form-control">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="status">
                                        <option value='0'>Все</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select id="owner" class="form-control" name="owner">
                                        <option value='0'>Владелец</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="active_from" placeholder="Период действия с" id="active_from" class="form-control datepicker-here">
                                </div>
                                <div class="col-4">
                                    <input  autocomplete="off" type="text" name="active_after" placeholder="Период действия по" id="active_after" class="form-control datepicker-here">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-2">
                     <button id="clean" class="btn btn-primary"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                     <a href="{{route('type-content.create')}}" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg" aria-hidden="true"></span></a>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th style="white-space: nowrap">Тип контента</th>
                <th>Описание</th>
                <th>Версия</th>
                <th>Статус</th>
                <th>Период действия</th>
                <th>Дата последнего редактирования</th>
                <th>Действия</th>
            </tr>
            @foreach($type_contents as $type_content)
                <tr>
                    <td style="white-space: nowrap"><i class="fa {{$type_content->icon}} fa-lg"
                                                       aria-hidden="true"></i> {{$type_content->name}}</td>
                    <td>{{$type_content->description}}</td>
                    <td>{{$type_content->version_major}}.{{$type_content->version_minor}}</td>

                    @if($type_content->status == 'Draft')
                        <td class="text-dark">Черновик</td>
                    @elseif($type_content->status == 'Published')
                        <td class="text-success">Опубликовано</td>
                    @elseif($type_content->status == 'Archive')
                        <td class="text-warning">В архиве</td>
                    @else
                        <td class="text-danger">Черновик</td>
                    @endif
                    <?php
                    $ac_fr = date_create($type_content->active_from);
                    $ac_af = date_create($type_content->active_after);
                    ?>
                    @if(empty($type_content->active_from) and empty($type_content->active_after))
                        <td>Не задан</td>
                    @elseif(empty($type_content->active_from) and !empty($type_content->active_after))
                        <td>До {{date_format($ac_af, 'd.m.Y')}}</td>
                    @elseif(!empty($type_content->active_from) and empty($type_content->active_after))
                        <td>{{date_format($ac_fr, 'd.m.Y')}}-бессрочно</td>
                    @else
                        <td>{{date_format($ac_fr, 'd.m.Y')}}-{{date_format($ac_af, 'd.m.Y')}}</td>
                    @endif
                    @if(!empty($type_content->updated_at))
                        <?php
                        $up_at = date_create($type_content->updated_at);
                        ?>
                        <td>{{date_format($up_at, 'd.m.Y H:m')}}</td>
                    @endif

                    <td nowrap>
                        <a href="{{route('type-content.get-all-version', $type_content->id_global)}}"
                           class="btn btn-primary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
        </table>
        {{--        <a href="{{route('type-content.create-icons')}}" class="btn btn-warning form-control">Сделать крутые иконки!</a>--}}
    </div>
    <style>
        .header-block{
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
        }
    </style>

    <script>

        $('.form').hide();
        $('#hideshow').on('click', function(event) {
            $('.form').toggle('show');
        });
        $("#clean").click(function() {
            $( 'form' ).each(function(){
                this.reset();
            });
        });
    </script>
@endsection
