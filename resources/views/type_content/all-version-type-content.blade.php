<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <div class="d-flex justify-content-center"><h1>Все версии одного global</h1></div>
        <a href="{{route('type-content.index')}}" class="btn btn-outline-info form-control">Вернуться</a>
        <table class="table table-bordered table-hover">
            <tr>
                <th>Версия</th>
                <th>Статус</th>
                <th>Идентификатор версии</th>
                <th>Дата последнего обновления</th>
                <th>Действия</th>
            </tr>
            @foreach($type_contents as $type_content)
                <tr>
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
                    <td>{{$type_content['id']}}</td>
                    <td>{{$type_content->updated_at}}
                        (@if(!empty($type_content->updated_authors)) {{$type_content->updated_authors->name}}@endif )</td>
                                <td nowrap>
                                    <a href="{{route('type-content.show', ($type_content->id))}}"
                                       class="btn btn-success ">
                                        <img src="{{asset('images/show.png')}}" width="32" height="32" alt="">
                                    </a>
                                    <a href="{{route('type-content.edit', $type_content->id)}}"
                                       class="btn btn-success">
                                        <img src="{{asset('images/edit.png')}}" width="32" height="32" alt="">
                                    </a>
                                </td>
                </tr>
            @endforeach
        </table>
        {{--    <a href="{{route('type-content.create')}}" class="btn btn-primary form-control">Создать</a>--}}
    </div>
@endsection
