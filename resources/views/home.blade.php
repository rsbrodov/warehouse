@extends('admin.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{--            <div class="col-md-8"><a class="btn btn-info" href="{{ route('users.index') }}"><img src="{{asset('images/new_user.png')}}" width="32" height="32" alt=""></a>--}}
            {{--           <a class="btn btn-danger" href="{{ route('users.roles-create-view') }}"><img src="{{asset('images/roles.png')}}" width="32" height="32" alt=""></a>--}}
            {{--           <a class="btn btn-outline-warning" href="{{ route('dictionary.index') }}"><img src="{{asset('images/dictionary.png')}}" width="32" height="32" alt=""></a></div>--}}
            {{--                        <a class="btn btn-warning" href="{{ route('link.handler', 'fuckinglinkingnotless50synBOLsehehelol') }}"> Тест ссылки 1</a>--}}
            <a class="btn btn-primary" href="{{ route('home-gvt') }}"> Тест GridView</a>
            <a class="btn btn-danger" href="{{ route('home-test') }}">Создать 10000 типов контента</a>
            <a class="btn btn-outline-primary" href="{{ route('home-delete-test10000TC') }}">Удалить 10000 типов контента</a>
            <a class="btn btn-warning" href="{{ route('home-test2') }}">Создать 10000 справочников</a>
            <a class="btn btn-outline-primary" href="{{ route('home-delete-test10000D') }}">Удалить 10000 справочников</a>
            <form action="{{route('image.upload')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-group">
                    <input type="file" name="image">
                </div>
                <button class="btn-default" type="submit">Загрузка</button>
            </form>
            @isset($path)
                <img class="img-fluid" src="{{asset('/storage/' . $path)}}">
            @endisset
            @can('read-component')
                <a class="btn btn-success" href="{{ route('tech.create') }}"> Create New Role</a>
            @endcan
            @auth
                <p class="text-center">Auth </p>
            @endauth
        </div>
    </div>

@endsection
