<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <?php
    $ac_fr = date_create($type_content->active_from);
    $ac_af = date_create($type_content->active_after);
    ?>
    <div class="container-fluid">
        <!-- Stack the columns on mobile by making one full-width and the other half-width -->
        <div class="row">
            <div class="col"><b><h1>{{$type_content->name}}</h1></b></div>
            <div class="col"></div>
        </div>

        <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
        <div class="row">
            <div class="col-3"><b>Идентификатор:</b> {{$type_content->id}}</div>
            <div class="col-1"><b>API URL:</b> {{$type_content->api_url}}</div>
            <div class="col-1"><b>Владелец:</b> {{$type_content->owner}}</div>
            <div class="col">
                <b>Период действия:</b>
                @if(empty($type_content->active_from) and empty($type_content->active_after))
                    Не задан
                @elseif(empty($type_content->active_from) and !empty($type_content->active_after))
                    До {{date_format($ac_af, 'd.m.Y')}}
                @elseif(!empty($type_content->active_from) and empty($type_content->active_after))
                    {{date_format($ac_fr, 'd.m.Y')}}-бессрочно
                @else
                    {{date_format($ac_fr, 'd.m.Y')}}-{{date_format($ac_af, 'd.m.Y')}}
                @endif
            </div>
            <div class="col-3"><a href="{{route('type-content.edit', $type_content->id)}}" class="btn btn-outline-secondary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></div>
        </div>

        <!-- Columns are always 50% wide, on mobile and desktop -->
        <div class="row">
            <div class="col-1">
                <b>Статус:</b>
                @if($type_content->status == 'Draft')
                    Черновик
                @elseif($type_content->status == 'Published')
                    Опубликовано
                @elseif($type_content->status == 'Archive')
                    В архиве
                @else
                    Черновик
                @endif
            </div>
            <div class="col-1"><b>Версия:</b> {{$type_content->version_major}}.{{$type_content->version_minor}}</div>
        </div>
        <div class="row">
            <div class="col-2"><a href="" class="btn btn-outline-secondary form-control">Состав полей</a></div>
            <div class="col-2"><a href="" class="btn btn-outline-secondary form-control">Доступ</a></div>
            <div class="col-2"><a href="{{route('type-content.get-all-version', $type_content->id_global)}}" class="btn btn-outline-secondary form-control">История изменений</a></div>
        </div>
        {{--    <a href="{{route('type-content.create')}}" class="btn btn-primary form-control">Создать</a>--}}
    </div>
@endsection
