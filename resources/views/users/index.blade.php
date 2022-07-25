<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <table class="table table-bordered table-hover mt-5">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Создан</th>
                <th>Действия</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
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
                    <td nowrap>
                        <a href="{{route('users.show', ($user->id))}}"
                           class="btn btn-success ">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        @if($user->status != 'MODERATED')
                            <a href="{{route('users.edit', ($user->id))}}"
                               class="btn btn-primary @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif ">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="{{route('users.activate', ($user->id))}}"
                               class="btn btn-success @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif ">
                                <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if($user->id != \Illuminate\Support\Facades\Auth::id())
                            <a href="{{route('users.delete', $user->id)}}" class="btn btn-danger">
                                @if($user->status == 'DELETED')
                                    <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                @endif
                            </a>
                            <a href="{{route('users.block', $user->id)}}"
                               class="btn btn-secondary @if($user->status == 'DELETED') disabled @endif">
                                @if($user->status == 'BLOCKED')
                                    <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-recycle fa-lg" aria-hidden="true"></i>
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
