<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="create col text-right">
                    <a href="{{route('users.create')}}" class="btn-create btn btn-outline-primary btn-unbordered"><span class="fa fa-plus-circle fa-lg"></span></a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>ID</th>
                <th>ФИО</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Создан</th>
                <th>Действия</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}} <img src="{{asset('/storage/' . $user->photo)}}" width="50" height="50" class="img-circle " alt="User Image"></td>
                    <td>{{$user->email}}</td>
                    <td class="
                    @if(\App\Models\User::find($user->id)->status == 'DELETED')
                        text-danger
                    @elseif($user->status == 'ACTIVATED')
                        text-success
                    @elseif($user->status == 'BLOCKED')
                        text-warning
                    @else
                        text-secondary
                    @endif
                        ">{{$user->status}}</td>
                    <td>{{date_format($user->created_at, 'd.m.Y')}}</td>
                    <td nowrap>
                        <a href="{{route('users.show', ($user->id))}}"
                           class="btn btn-outline-success btn-unbordered" title="Посмотреть">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        @if($user->status != 'MODERATED')
                            <a href="{{route('users.edit', ($user->id))}}"
                               class="btn btn-outline-primary btn-unbordered @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif " title="Редактировать">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="{{route('users.activate', ($user->id))}}"
                               class="btn btn-outline-success btn-unbordered @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif " title="Подтвердить">
                                <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if($user->id != \Illuminate\Support\Facades\Auth::id())
                            <a href="{{route('users.delete', $user->id)}}" class="btn btn-outline-danger btn-unbordered" title="Удалить">
                                @if($user->status == 'DELETED')
                                    <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                @endif
                            </a>
                            <a href="{{route('users.block', $user->id)}}"
                               class="btn btn-outline-secondary btn-unbordered @if($user->status == 'DELETED') disabled @endif" title="Заблокировать">
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
    </div>

@endsection
