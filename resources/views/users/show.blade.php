<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container">
        <h1> {{ $user->name }}</h1>
        <div class="jumbotron text-center">
            <h2>{{ $user->name }}</h2>

                <strong>ID:</strong> {{ $user->id }}<br>
                <strong>Имя:</strong> {{ $user->name }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Создатель:</strong> {{User::where('id', $user->parent_id)->first()->name}}<br>
                <strong>Статус:</strong>
            <div class="p-3 mb-2
            @if($user->status == 'ACTIVATED')
                bg-success text-white
            @elseif($user->status == 'MODERATED')
                bg-secondary text-white
            @elseif($user->status == 'BLOCKED')
                bg-warning text-white
            @elseif($user->status == 'DELETED')
                bg-danger text-white
            @endif
                ">{{ $user->status }}</div>
            <strong>Создан:</strong> {{ $user->created_date }}<br>
            <strong>Обновлен:</strong> {{ $user->update_date }}<br>
            <a href="{{route('users.index')}}" class="btn btn-primary form-control">Вернуться</a>
        </div>
    </div>
@endsection
