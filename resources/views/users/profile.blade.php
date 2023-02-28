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
            <div class="col-3">
                {{-- @if($user->photo)
                <img class="img-fluid" src="{{asset('/storage/' . $user->photo)}}">
                @else --}}
                {{-- <img class="img-fluid" src="{{asset('/storage/profile.png')}}"> --}}
                <img src="{{asset('/storage/' . $result['user']->photo)}}" width="255" height="255" class="img-circle mb-2" alt="User Image">
                <form action="{{route('users.profile-image-upload', [$result['user']->id])}}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input class="form-control" type="file" name="image">
                    <button class="form-control btn-outline-success" style="margin-top:10px;" type="submit">Обновить фото профиля</button>
                </form>

                <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <button class="form-control btn-danger">Выйти из личного кабинета</button>
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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Введите имя" value="{{$result['user']->name}}">
                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="email">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Введите email" value="{{$result['user']->email}}">
                        @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="email">Роль</label>
                        <input type="text" class="form-control" disabled id="role" name="role" value="{{$result['role']}}">
                        <label for="password">Новый пароль</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}">
                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        <label for="password">Подтвердите новый пароль</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" value="{{old('password_confirmation')}}">
                        <button type="submit" class="form-control btn-outline-success mt-3">Редактировать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
