<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div class="col-md-4"><h3>Контентная модель</h3></div>
            <div class="col-md-1 ml-auto"><a href="{{route('type-content.create')}}" class="btn-create btn btn-primary"><span
                        class="fa fa-plus-circle fa-lg" aria-hidden="true"></span></a></div>
        </div>
        <table class="table table-bordered table-hover">
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
                    <td style="white-space: nowrap"><i class="fa {{$type_content->icon}} fa-lg" aria-hidden="true"></i> {{$type_content->name}}</td>
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

                    <td>{{$type_content->active_from}}-{{$type_content->active_after}}</td>
                    <td>{{$type_content->updated_at}}
                        (@if(!empty($type_content->updated_authors)) {{$type_content->updated_authors->name}}@endif )</td>
                    <td nowrap>
                        <a href="{{route('type-content.get-all-version', $type_content->id_global)}}" class="btn btn-primary"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
        </table>
        {{--        <a href="{{route('type-content.create-icons')}}" class="btn btn-warning form-control">Сделать крутые иконки!</a>--}}
    </div>
    <style>
        .btn-create span{
            background: #1890FF;
            border-radius: 50%!important;
        }
    </style>
@endsection
