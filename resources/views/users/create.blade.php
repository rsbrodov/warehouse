<?use \App\Models\User;?>
{{--@extends('admin.main')--}}
@extends('layouts.app')
@section('content')
<div class="container">

    <h1>Создание пользователя</h1>
    <form action="{{route('users.store')}}" method="post">
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

    </form>
</div>
@endsection
