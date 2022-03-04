@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование данных пользователя {{$edit_user->name}}</h1>
        <form action="{{route('users.update', [$edit_user])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя" value="{{$edit_user->name}}">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Введите email" value="{{$edit_user->email}}">
                <button type="submit" class="btn btn-success">Редактировать</button>
            </div>
        </form>
    </div>
@endsection
