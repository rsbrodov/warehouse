@extends('admin.main')
@section('content')
    <div class="container">
        <h1> {{ $type_content->name }}</h1>
        <div class="jumbotron text-center">
            <h2>{{ $type_content->name }}</h2>

            <strong>ID:</strong> {{ $type_content->id }}<br>
            <strong>ID:</strong> {{$type_content->id_global}}<br>
            <strong>Имя:</strong> {{ $type_content->name }}<br>
            <strong>Описание:</strong> {{$type_content->description}}<br>
            <strong>Владелец:</strong> {{$type_content->owner}}<br>
            <strong>Иконка:</strong> {{$type_content->icon}}<br>
            <strong>Активен с:</strong> {{$type_content->active_from}}<br>
            <strong>Активен по:</strong> {{$type_content->active_after}}<br>
            <strong>Статус:</strong>
            <div class="p-3 mb-2
            @if($type_content->status == 'Published')
                bg-success text-white
            @elseif($type_content->status == 'Draft')
                bg-secondary text-white
            @elseif($type_content->status == 'Archive')
                bg-warning text-white
            @elseif($type_content->status == 'Other')
                bg-danger text-white
            @endif
                ">{{ $type_content->status }}</div>
            <strong>Версия:</strong> {{ $type_content->version_major}}.{{$type_content->version_minor }}<br>
            <strong>API URL:</strong> {{ $type_content->api_url }}<br>
            <strong>Body:</strong> {{ $type_content->body }}<br>
            <strong>Based type:</strong> {{ $type_content->based_type }}<br>
            <strong>Создан:</strong> {{ $type_content->created_at }}<br>
            <strong>Кем создан:</strong>{{\App\Models\User::where('id', $type_content->created_author)->first()->name}}<br>
            <strong>Обновлен:</strong> {{ $type_content->updated_at }}<br>
            <strong>Кем обновлен:</strong> {{\App\Models\User::where('id', $type_content->updated_author)->first()->name}}<br>
            <a href="{{route('type-content.index')}}" class="btn btn-primary form-control">Вернуться</a>
        </div>
    </div>
@endsection
