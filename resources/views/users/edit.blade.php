@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование данных пользователя {{$edit_user->name}}</h1>
        <form action="{{route('users.update', [$edit_user])}}" method="post">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Введите имя" value="{{$edit_user->name}}">
                @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <label for="email">Email</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Введите email" value="{{$edit_user->email}}">
                @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <button type="submit" class="btn btn-success">Редактировать</button>
                <form action="{{ route('users.destroy', $edit_user->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button disabled type="submit" class="btn btn-danger"><i class="fa fa-trash-o fa-lg fa-spin" aria-hidden="true"></i></button>
                </form>
            </div>
        </form>
    </div>
@endsection


