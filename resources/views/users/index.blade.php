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
            <tr class="text-center">
                <th>ID</th>
                <th>Фото</th>
                <th>ФИО</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Создан</th>
                <th class="text-left">Действия</th>
            </tr>
            @foreach($users as $user)
                <tr class="">
                    <td class="text-center align-middle">{{$user->id}}</td>
                    <td class="text-center align-middle"><img src="{{asset('/storage/' . $user->photo)}}" width="75" height="75" class="img-circle " alt="User Image"></td>
                    <td class="text-center align-middle">{{$user->name}}</td>
                    <td class="text-center align-middle">{{$user->email}}</td>
                    <td class="text-center align-middle
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
                    <td class="text-center align-middle">{{date_format($user->created_at, 'd.m.Y')}}</td>
                    <td nowrap>
                        <a href="{{route('users.show', ($user->id))}}"
                           class="btn btn-outline-primary btn-unbordered align-middle" title="Посмотреть" style="margin-top: 20px;">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        @if($user->status != 'MODERATED')
                            <a href="{{route('users.edit', ($user->id))}}"
                               class="btn btn-outline-primary btn-unbordered text-center align-middle @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif "  style="margin-top: 20px;" title="Редактировать">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="{{route('users.activate', ($user->id))}}"
                               class="btn btn-outline-primary btn-unbordered text-center align-middle @if($user->status == 'DELETED' or $user->status == 'BLOCKED') disabled @endif " style="margin-top: 20px;" title="Подтвердить">
                                <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if($user->id != \Illuminate\Support\Facades\Auth::id())
                            <a href="{{route('users.delete', $user->id)}}" class="btn btn-outline-primary btn-unbordered align-middle" style="margin-top: 20px;" title="Удалить">
                                @if($user->status == 'DELETED')
                                    <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                @endif
                            </a>
                            <a href="{{route('users.block', $user->id)}}"
                               class="btn btn-outline-primary btn-unbordered align-middle @if($user->status == 'DELETED') disabled @endif" style="margin-top: 20px;" title="Заблокировать">
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
