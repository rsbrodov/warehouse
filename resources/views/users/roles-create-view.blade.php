@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="">
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>
        <div class="row align-items-start">
            <div class="col">

                <div class="d-flex justify-content-center"><h1>Создание ролей</h1></div>
                <form action="{{route('users.roles-create-form', 'role')}}" method="post">
                    @csrf
                    <div class="form-group col-md-6">
                        <label for="role">Роль</label>
                        <input type="text" class="form-control" id="role" name="role" placeholder="Введите название роли">
                        Дать роли какие-либо полномочия?
                        @foreach($permissions = \Spatie\Permission\Models\Permission::all() as $permission)
                            <div>
                                <input type="checkbox" id="permissions" name="permissions[]" value="{{$permission->name}}">
                                <label for="permission">{{$permission->name}}</label>
                            </div>
                        @endforeach
                        {{-- @error('permissions') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror --}}
                        <button type="submit" class="btn btn-success">Создать</button>
                    </div>
                </form>
            </div>
            <div class="col">
                <div class="d-flex justify-content-center"><h1>Создание полномочий</h1></div>
                <form action="{{route('users.roles-create-form', 'permission')}}" method="post">
                    @csrf
                    <div class="form-group col-md-6">
                        <label for="permission">Полномочие</label>
                        <input type="text" class="form-control" id="permission" name="permission"
                               placeholder="Введите название полномочия">
                        Присвоить данное полномочие какой-либо роли?
                        @foreach($roles = \Spatie\Permission\Models\Role::all() as $role)
                            <div>
                                <input type="checkbox" id="role" name="roles[]" value="{{$role->name}}">
                                <label for="permission">{{$role->name}}</label>
                            </div>
                        @endforeach
                        {{-- @error('roles') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror --}}
                        <button type="submit" class="btn btn-success">Создать</button>
                    </div>
                </form>
            </div>
            <div class="col">
                <div class="d-flex justify-content-center"><h1>Присваивание пользователю роли</h1></div>
                <form action="{{route('users.roles-assign')}}" method="post">
                    @csrf
                    <div class="form-group col-md-6">
                        <label for="role">Роль</label>

                            <select id="role" type="text" class="form-control" name="role">
                                {{-- @foreach($roles = \Spatie\Permission\Models\Role::where('name', '<>','SuperAdmin')->get() as $role) --}}
                                @foreach($roles = \Spatie\Permission\Models\Role::get() as $role)
                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                @endforeach
                            </select>

                        Каким пользователям присвоить роль?
                        @foreach($users = \App\Models\User::all() as $user)
                            <div>
                                <input type="checkbox" id="users" name="users[]" value="{{$user->name}}">
                                <label for="users">{{$user->name}}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-success">Выполнить</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex justify-content-center"><h1>Таблица Roles</h1></div>
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Полномочия</th>
                        <th>Created_date</th>
                        <th>Update_date</th>
                    </tr>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{$role->id}}</td>
                            <td>{{$role->name}}</td>
                            <td>@foreach($role->permissions as $permission) {{$permission->name}}, @endforeach </td>
                            <td>{{$role->created_date}}</td>
                            <td>{{$role->update_date}}</td>
                            <td><a href="{{route('users.delete-role', ($role->id))}}"
                                   class="btn btn-success ">
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                </a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col">
                <div class="d-flex justify-content-center"><h1>Таблица Permissions</h1></div>
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Created_date</th>
                        <th>Update_date</th>
                    </tr>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{$permission->id}}</td>
                            <td>{{$permission->name}}</td>
                            <td>{{$permission->created_date}}</td>
                            <td>{{$permission->update_date}}</td>
                            <td><a href="{{route('users.delete-permission', ($permission->id))}}"
                                   class="btn btn-success ">
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                </a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col">
                Просто случайный текст в случайном месте:)
            </div>
        </div>
{{--        <div class="row align-items-end">--}}
{{--            <div class="col">--}}
{{--                Одна из трёх колонок--}}
{{--            </div>--}}
{{--            <div class="col">--}}
{{--                Одна из трёх колонок--}}
{{--            </div>--}}
{{--            <div class="col">--}}
{{--                Одна из трёх колонок--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
