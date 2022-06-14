<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container">
        <table class="table table-bordered mt-2 table-hover">
            <tr>
                <th>Название</th>
                <th>Версия</th>
                <th>Статус</th>
                <th>Идентификатор версии</th>
                <th>Автор изменений</th>
                <th>Дата и время изменений</th>
                <th>Действия</th>
            </tr>
            @foreach($element_contents as $element_content)
                <tr>
                    <td>{{$element_content->label}}</td>
                    <td>{{$element_content->version_major}}.{{$element_content->version_minor}}</td>
                    @if($element_content->status == 'Draft')
                        <td class="text-dark">Черновик</td>
                    @elseif($element_content->status == 'Published')
                        <td class="text-success">Опубликовано</td>
                    @elseif($element_content->status == 'Archive')
                        <td class="text-warning">В архиве</td>
                    @elseif($element_content->status == 'Destroy')
                        <td class="text-danger">На удаление</td>
                    @endif
                    <td>{{$element_content->id}}</td>

                    <td>@if(!empty($element_content->updated_authors)) {{$element_content->updated_authors->name}}@endif </td>
                    <td>{{$element_content->updated_at}}</td>
                    <td style="display: flex">
                        <div class="col-4">
                            <a href="{{route('element-content.edit', $element_content->id)}}" class="btn btn-primary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
            <a href="{{route('element-content.create', $element_content->type_content_id)}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
