@extends('admin.main')
@section('content')
    <div class="container">
        <h1> Просмотр контента</h1>
        <div class="jumbotron text-center">
            <h2>{{ $type_content->name }}</h2>
            <strong>Версия:</strong> {{ $type_content->version_major}}.{{$type_content->version_minor }}<br>
            <?php
            $names = [
                ['ID', 'id'],
                ['ID GLOBAL', 'id_global'],
                ['Имя', 'name'],
                ['Описание', 'description'],
                ['Владелец', 'owner'],
                ['Иконка', 'icon'],
                ['Активен c', 'active_from'],
                ['Активен по', 'active_after'],
                ['Статус', 'status'],
                ['Версия мажор', 'version_major'],
                ['Версия минор', 'version_minor'],
                ['API URL', 'api_url'],
                ['Body', 'body'],
                ['Based type', 'based_type'],
                ['Создан', 'created_at'],
                ['Кем создан', 'created_authors'],
                ['Обновлен', 'updated_at'],
                ['Кем обновлен', 'updated_authors'],
            ];
            $version = '';
            ?>
            <table class="table table-bordered table-hover">
                @foreach($names as [$name, $db_name])
                    <tr>
                        <th>{{$name}}</th>
                        @if($db_name =='created_authors' or $db_name =='updated_authors')
                            <td>{{$type_content->$db_name->name}}</td>
                        @elseif($db_name =='status')
                            <td class="
                            @if($type_content->status == 'Published')
                                bg-success text-white
                            @elseif($type_content->status == 'Draft')
                                bg-secondary text-white
                            @elseif($type_content->status == 'Archive')
                                bg-warning text-white
                            @elseif($type_content->status == 'Other')
                                bg-danger text-white
                            @endif
                                ">{{$type_content->$db_name}}</td>
                            @elseif($db_name =='icon')
                                <td><span class="fa fa-{{$type_content->$db_name}} fa-lg" aria-hidden="true"></span> {{$type_content->$db_name}}</td>
                            @else
                                <td>{{$type_content->$db_name}}</td>
                        @endif
                    </tr>
                @endforeach
            </table>
            <a href="{{route('type-content.index')}}" class="btn btn-primary form-control">Вернуться</a>
        </div>
    </div>
@endsection
