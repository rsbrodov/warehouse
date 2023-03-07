<?use \App\Models\User;?>
@extends('admin.main')
@section('content')
<div class="mt-5 ml-2 col-6">
    <form action="{{route('users.store')}}" method="post">
        @csrf
        <label for="name"><b class="text-danger">*</b><b>Имя</b></label>
        <input autocomplete="off" id="name" name="name" placeholder="Введите имя" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="email" class="mt-3"><b class="text-danger mt-3">*</b><b>Email</b></label>
        <input autocomplete="off" id="email" name="email" placeholder="Введите email" class="form-control" type="text" @error('email') is-invalid @enderror value="{{ old('email') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="password" class="mt-3"><b class="text-danger">*</b><b>Пароль</b></label>
        <input autocomplete="off" id="password" name="password" placeholder="Введите пароль" required autocomplete="current-password" class="form-control" type="password" @error('password') is-invalid @enderror value="{{ old('password') }}">
        @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="password_confirmation" class="mt-3"><b class="text-danger">*</b><b>Подтвердите пароль</b></label>
        <input autocomplete="off" id="password_confirmation" name="password_confirmation" required autocomplete="current-password" placeholder="Введите пароль" class="form-control" type="password" @error('password') is-invalid @enderror value="{{ old('password_confirmation') }}">
        @error('password_confirmation') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('SuperAdmin'))
            <label for="role" class="mt-3"><b>Роль</b></label>
            <select autocomplete="off" id="role" name="role" class="form-control">
                @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->name}}</option>
                @endforeach
            </select>
        @endif
        <button type="submit" class="btn btn-primary mt-3" style="width: 100%">Создать</button>

    </form>
</div>
@endsection
