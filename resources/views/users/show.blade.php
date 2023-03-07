<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container-fluid">
        <div class="">
            <h2 class="mt-3">{{ $user->name }}</h2>
                <p><b>ID: </b>{{ $user->id }}</p>
                <p><b>Имя: </b>{{ $user->name }}</p>
                <p><b>Email: </b>{{ $user->email }}</p>
                <p><b>Создатель: </b>{{User::where('id', $user->parent_id)->first()->name}}</p>
                <p><b>Статус: </b><span class="
            @if($user->status == 'ACTIVATED')
                        text-success
            @elseif($user->status == 'MODERATED')
                        text-secondary
            @elseif($user->status == 'BLOCKED')
                        text-warning
            @elseif($user->status == 'DELETED')
                        text-danger
            @endif">{{ $user->status }}</span></p>
            <p><b>Создан: </b>{{ date('d.m.Y', strtotime($user->created_at)) }}</p>
            <p><b>Обновлен: </b>{{ date('d.m.Y', strtotime($user->updated_at)) }}</p>
            <a href="{{route('users.index')}}" class="btn btn-primary">Вернуться к списку</a>
        </div>
    </div>
@endsection
