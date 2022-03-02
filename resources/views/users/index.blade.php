<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div>
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>
        <div class="d-flex justify-content-center"><h1>Пользователи</h1></div>
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
                    <td>{{\App\Models\User::where('id', $user->parent_id)->first()->name}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td class="
                    @if(\App\Models\User::find($user->id)->status == 'DELETED')
                        bg-danger
                    @elseif($user->status == 'ACTIVATED')
                        bg-success
                    @elseif($user->status == 'BLOCKED')
                        bg-warning
                    @else
                        bg-secondary
                    @endif
                        ">{{$user->status}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->updated_at}}</td>
                    <td>
                        <a href="{{route('users.show', ($user->id))}}"
                           class="btn btn-success ">
                            <img src="{{asset('images/show.png')}}" width="32" height="32" alt="">
                        </a>
                        @if($user->status != 'MODERATED')
                            <a href="{{route('users.edit', ($user->id))}}"
                               class="btn btn-primary @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif ">
                                <img src="{{asset('images/edit.png')}}" width="32" height="32" alt="">
                            </a>
                        @else
                            <a href="{{route('users.activate', ($user->id))}}"
                               class="btn btn-success @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif ">
                                <img src="{{asset('images/ok.png')}}" width="32" height="32" alt="">
                            </a>
                        @endif
                        @if($user->id != \Illuminate\Support\Facades\Auth::id())
                            <a href="{{route('users.delete', $user->id)}}" class="btn btn-secondary">
                                @if($user->status == 'DELETED')
                                    <img src="{{asset('images/revive.png')}}" width="32" height="32" alt="">
                                @else
                                    <img src="{{asset('images/delete.png')}}" width="32" height="32" alt="">
                                @endif
                            </a>
                            <a href="{{route('users.block', $user->id)}}"
                               class="btn btn-secondary @if($user->status == 'DELETED') disabled @endif">
                                @if($user->status == 'BLOCKED')
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
        <a href="{{route('users.create')}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
