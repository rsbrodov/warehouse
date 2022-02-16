{{--@extends('admin.main')--}}
@extends('layouts.app')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>
        <div class="d-flex justify-content-center"><h1>Создание ролей</h1></div>
        <form action="{{route('users.roles-create-form', ['role'])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="role">Роль</label>
                <input type="text" class="form-control" id="role" name="role" placeholder="Введите название роли">

                <button type="submit" class="btn btn-success">Создать</button>
            </div>
        </form>

        <div class="d-flex justify-content-center"><h1>Создание полномочий</h1></div>
        <form action="{{route('users.roles-create-form', ['permission'])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="permission">Полномочие</label>
                <input type="text" class="form-control" id="permission" name="permission" placeholder="Введите название полномочия">
                <button type="submit" class="btn btn-success">Создать</button>
            </div>
        </form>
        <div class="d-flex justify-content-center"><h1>Таблица Roles</h1></div>
        <table class="table table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created_at</th>
                <th>Updated_at</th>
            </tr>
            @foreach($roles as $role)
                <tr>
                    <td>{{$role->id}}</td>
                    <td>{{$role->name}}</td>
                    <td>{{$role->created_at}}</td>
                    <td>{{$role->updated_at}}</td>

                </tr>
            @endforeach
        </table>
        <div class="d-flex justify-content-center"><h1>Таблица Permissions</h1></div>
            <table class="table table-bordered table-hover">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                </tr>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->id}}</td>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->created_at}}</td>
                    <td>{{$permission->updated_at}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
