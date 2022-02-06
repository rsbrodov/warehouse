
@extends('admin.main')
@section('content')
<h1>Привет, {{$user_login}}! Твой ID: {{$user_id}} </h1>
<h1>Создание пользователя</h1>
<form action="{{route('yurk.user-create-form')}}" method="post">
    @csrf
    <div class="form-group">
        <label for="name">Имя</label>
        <input type="text" name="name" placeholder="Введите имя" id="name" class="form-control">
        <label for="name">Пароль</label>
        <input type="text" name="password" placeholder="Введите пароль" id="password" class="form-control">
        <label for="confirm_password">Подтвердите пароль</label>
        <input type="text" name="confirm_password" placeholder="Подтвердите пароль" id="confirm_password" class="form-control">
        <label for="email">Email</label>
        <input type="text" name="email" placeholder="Введите email" id="email" class="form-control">
        <input type="hidden" name="parent_id" id="parent_id" value="{{$user_id}}" class="form-control">
        <input type="hidden" name="status" id="status" value="moderated" class="form-control">
        <button type="submit" class="btn btn-success">Создать</button>
    </div>
</form>
</br>
</br>
<h1>Таблица Users</h1>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>Создатель</th>
        <td>Имя</td>
        <td>Email</td>
        <td>Статус</td>
        <td>Ссылка для подтверждения</td>
        <td>Создан</td>
        <td>Обновлен</td>
    </tr>
        @foreach($users as $user)
        <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->parent_id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->status}}</td>
            <td>{{$user->link}}</td>
            <td>{{$user->created_at}}</td>
            <td>{{$user->updated_at}}</td>
        </tr>
        @endforeach
</table>
@endsection
