{{--@extends('admin.main')--}}
@extends('layouts.app')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <?use \App\Models\User;?>
    <div class="container">
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>
        <h1>Привет, {{$user_login}}! Твой ID: {{$user_id}} </h1>
        <h1>Создание пользователя</h1>
        <form action="{{route('users.user-create-form')}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                <div class="col-md-6">
                    <input type="text" name="name" placeholder="Введите имя" id="name" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                <div class="col-md-6">
                    <input type="text" name="email" placeholder="Введите email" id="email" class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password_confirmation" required autocomplete="current-password">
                </div>
            </div>
            @if(Auth::user()->hasRole('Admin'))
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                    <div class="col-md-6">
                        <select id="role" type="text" class="form-control" name="role">
                            @foreach($roles = \Spatie\Permission\Models\Role::where('name', '<>','SuperAdmin')->get() as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <button type="submit" class="btn btn-success">Создать</button>
    </div>
    </form>
    </br>
    </br>

    <div class="d-flex justify-content-center"><h1>Таблица Users</h1></div>
    <div class="container">
        <table class="table table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Создатель</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Создан</th>
                <th>Обновлен</th>
                <th>Действия</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{User::where('id', $user->parent_id)->first()->name}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td class="
                    @if(User::find($user->id)->status == 'DELETED')
                        bg-danger
                    @elseif(User::find($user->id)->status == 'ACTIVATED')
                        bg-success
                    @elseif(User::find($user->id)->status == 'BLOCKED')
                        bg-warning
                    @else
                        bg-secondary
                    @endif
                        ">{{$user->status}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->updated_at}}</td>
                    <td>
                        @if(User::find($user->id)->status != 'MODERATED')
                            <a href="{{route('users.user-edit-view', ($user->id))}}"
                               class="btn btn-primary @if(User::find($user->id)->status == 'DELETED' or User::find($user->id)->status == 'BLOCKED') disabled @endif ">
                                <img src="{{asset('images/edit.png')}}" width="32" height="32" alt="">
                            </a>
                        @else
                            <a href="{{route('users.user-activate-button', ($user->id))}}"
                               class="btn btn-success @if(User::find($user->id)->status == 'DELETED' or User::find($user->id)->status == 'BLOCKED') disabled @endif ">
                                <img src="{{asset('images/ok.png')}}" width="32" height="32" alt="">
                            </a>
                        @endif
                        @if($user->id != $user_id)
                            <a href="{{route('users.user-delete-button', $user->id)}}" class="btn btn-secondary">
                                @if(User::find($user->id)->status == 'DELETED')
                                    <img src="{{asset('images/revive.png')}}" width="32" height="32" alt="">
                                @else
                                    <img src="{{asset('images/delete.png')}}" width="32" height="32" alt="">
                                @endif
                            </a>
                            <a href="{{route('users.user-block-button', $user->id)}}"
                               class="btn btn-secondary @if(User::find($user->id)->status == 'DELETED') disabled @endif">
                                @if(User::find($user->id)->status == 'BLOCKED')
                                    <img src="{{asset('images/revive.png')}}" width="32" height="32" alt="">
                                @else
                                    <img src="{{asset('images/block.png')}}" width="32" height="32" alt="">
                                @endif
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
