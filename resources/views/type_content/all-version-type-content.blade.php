<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <div class="d-flex justify-content-center"><h1>Все версии одного global</h1></div>
        <a href="{{route('type-content.index')}}" class="btn btn-outline-info form-control">Вернуться</a>
        <table class="table table-bordered mt-2 table-hover">
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
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('type-content.edit', $type_content->id)}}"
                           class="btn btn-primary">
                            <i class="fa fa-edit fa-lg" aria-hidden="true"></i>
                        </a>
                        <form action="{{ route('type-content.destroy', $type_content->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </form>
                        <a href="{{route('type-content.descript-version', $type_content->id)}}"
                           class="btn btn-primary">
                            <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                        </a>

                    </td>
                </tr>
            @endforeach
        </table>
        {{--    <a href="{{route('type-content.create')}}" class="btn btn-primary form-control">Создать</a>--}}
    </div>
@endsection
