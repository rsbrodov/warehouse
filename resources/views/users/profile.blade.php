<style>
    .flex-cont{
        display:inline-flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        width:66.66%;
    }
    .flex-elem{
        margin: 5px
    }
</style>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <!-- <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>   -->
        {{-- <div class="text-center"><h1>Личный кабинет пользователя {{$user->name}}</h1> </div> --}}
        <div class="row mt-5">
            <div class="col-3 mt-3">
                {{-- @if($user->photo)
                <img class="img-fluid" src="{{asset('/storage/' . $user->photo)}}">
                @else --}}
                {{-- <img class="img-fluid" src="{{asset('/storage/profile.png')}}"> --}}
                <img src="{{asset('/storage/' . $result['user']->photo)}}" width="255" height="255" class="img-circle " alt="User Image">
                <form action="{{route('users.profile-image-upload', [$result['user']->id])}}" method="post" enctype="multipart/form-data" class="mt-3">
                    @method('PUT')
                    @csrf
                    <input class="form-control" type="file" name="image">
                    <button class="form-control btn-outline-success mt-1" type="submit">Обновить фото профиля</button>
                </form>
                @if($result['user']->photo !== 'profiles/default.png')
                    <a href="{{route('users.profile-image-delete', [$result['user']->id])}}" class="btn btn-outline-danger form-control">Удалить фотографию</a>
                @endif
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <button class="form-control btn-danger mt-3">Выйти из личного кабинета</button>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                {{-- @endif --}}
            </div>
            <div class="col-9">
                <form action="{{route('users.profile-update', [$result['user']->id])}}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autocomplete="off" placeholder="Введите имя" value="{{$result['user']->name}}">
                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="email">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" autocomplete="off" placeholder="Введите email" value="{{$result['user']->email}}">
                        @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="email">Роль</label>
                        <input type="text" class="form-control" disabled id="role" name="role" value="{{$result['role']}}">

                        <label for="description">О себе</label>

                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="" maxlength="255" autocomplete="off" value="{{$result['user']->description}}"></textarea>
                        @error('description') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                        <label for="password">Новый пароль</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off" value="{{old('password')}}">
                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="password">Подтвердите новый пароль</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" autocomplete="off" value="{{old('password_confirmation')}}">
                        <button type="submit" class="form-control btn-outline-success mt-3">Редактировать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
