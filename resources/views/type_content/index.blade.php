<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <div class="d-flex justify-content-center"><h1>Типы контента</h1></div>
        <table class="table table-bordered table-hover">
            <tr>
                {{--                <th>ID</th>--}}
                {{--                <th>ID GLOBAL</th>--}}
                <th>Наименование</th>
                <th>Описание</th>
                {{--                <th>Владелец</th>--}}
                {{--                <th>Иконка</th>--}}
                {{--                <th>Активен от</th>--}}
                {{--                <th>Активен до</th>--}}
                <th>Версия</th>
                <th>Статус</th>
                {{--                <th>API URL</th>--}}
                {{--                <th>BODY</th>--}}
                {{--                <th>Based type</th>--}}
                {{--                <th>Создан</th>--}}
                {{--                <th>Создатель</th>--}}
                <th>Дата последнего обновления</th>
                <th>Действия</th>
            </tr>
            @foreach($type_contents as $type_content)
                <tr>
                    {{--                    <td>{{$type_content['id']}}</td>--}}
                    {{--                    <td>{{$type_content->id_global}}</td>--}}
                    <td>{{$type_content->name}}</td>
                    <td>{{$type_content->description}}</td>
                    {{--                    <td>{{$type_content->owner}}</td>--}}
                    {{--                    <td>{{$type_content->icon}}</td>--}}
                    {{--                    <td>{{$type_content->active_from}}</td>--}}
                    {{--                    <td>{{$type_content->active_after}}</td>--}}
                    <td>{{$type_content->version_major}}.{{$type_content->version_minor}}</td>
                    <td class="
                    @if($type_content->status == 'Draft')
                        bg-secondary
                    @elseif($type_content->status == 'ACTIVATED')
                        bg-success
                    @elseif($type_content->status == 'BLOCKED')
                        bg-warning
                    @else
                        bg-danger
                    @endif
                        ">{{$type_content->status}}</td>
                    {{--                    <td>{{$type_content->api_url}}</td>--}}
                    {{--                    <td>{{$type_content->body}}</td>--}}
                    {{--                    <td>{{$type_content->based_type}}</td>--}}
                    {{--                    <td>{{$type_content->created_at}}</td>--}}
                    {{--                    <td>@if(!empty($type_content->created_authors)) {{$type_content->created_authors->name}}@endif</td>--}}
                    <td>{{$type_content->updated_at}}
                        (@if(!empty($type_content->updated_authors)) {{$type_content->updated_authors->name}}@endif )</td>
                    <td nowrap>
{{--                        <a href="{{route('type-content.show', ($type_content->id))}}"--}}
{{--                           class="btn btn-success ">--}}
{{--                            <img src="{{asset('images/show.png')}}" width="32" height="32" alt="">--}}
{{--                        </a>--}}
                        <a href="{{route('type-content.get-all-version', $type_content->id_global)}}"
                           class="btn btn-success">
                            <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('type-content.create')}}" class="btn btn-primary form-control">Создать</a>
{{--        <a href="{{route('type-content.create-icons')}}" class="btn btn-warning form-control">Сделать крутые иконки!</a>--}}
    </div>
@endsection
