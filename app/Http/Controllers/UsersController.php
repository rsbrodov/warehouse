<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            //dd($user->hasRole('Admin'));
            if ($user->hasRole('SuperAdmin')) {
                $users = User::all();
            } else if ($user->hasRole('Admin')) {
                $users = User::where('parent_id', Auth::id())->orWhere('id', Auth::id())->get();
            }
            else{
                return redirect()->route('home')->with('success', 'У вас недостаточно полномочий');
            }
            return view('users.index')->with('users', $users);
        }
    }

    public function create()
    {
        if (Auth::guard('web')->check()) {
            $roles = \Spatie\Permission\Models\Role::where('name', '<>', 'SuperAdmin')->get();
            return view('users.create')->with('roles', $roles);
        }
    }

    public function store(UserRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $newUser = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => 'MODERATED',
                'parent_id' => Auth::id()
            ]);
            if ($user->hasRole('Admin')) {
                $newUser->assignRole($request->input('role')); //назначить роль юзеру
            }
            return redirect()->route('users.index')->with('success', 'Пользователь ' . $newUser->name . ' успешно добавлен');
        }
    }

    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $editUser = User::where('id', $id)->first();
                return view('users.edit', ['edit_user' => $editUser]); // вью - это физический файл, рут - это виртуальный путь, который ведет к методу
            }
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('user', $user);
    }

    public function update(UserRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $editUser = User::where('id', $id)->first();
                $editUser->name = $request->input('name');
                $editUser->email = $request->input('email');
                $editUser->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $editUser->name . ' успешно отредактирован');
            }
        }
    }

    public function activate($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $actUser = User::where('id', $id)->first();
                if ($actUser->status == 'MODERATED') {
                    $actUser->status = 'ACTIVATED';
                }
                $actUser->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $actUser->name . ' активирован!');
            }
        }
    }

    public function block($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $delUser = User::where('id', $id)->first();
                $message = ''; $typeMessage = 'success';
                if ($delUser->status == 'BLOCKED') {
                    $delUser->status = 'MODERATED';
                    $message = 'Пользователь ' . $delUser->name . ' разблокирован и находится на модерации';
                } else {
                    $delUser->status = 'BLOCKED';
                    $message = 'Пользователь ' . $delUser->name . ' заблокирован!';
                    $typeMessage = 'warning';
                }
                $delUser->save();
                return redirect()->route('users.index')->with($typeMessage, $message);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function delete($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $delUser = User::where('id', $id)->first();
                $message = ''; $typeMessage = 'success';
                if ($delUser->status == 'DELETED') {
                    $delUser->status = 'MODERATED';
                    $message = 'Пользователь ' . $delUser->name . ' восстановлен и находится на модерации';
                } else {
                    $delUser->status = 'DELETED';
                    $message = 'Пользователь ' . $delUser->name . ' удален!';
                    $typeMessage = 'info';
                }
                $delUser->save();
                return redirect()->route('users.index')->with($typeMessage, $message);
            }
        }
    }


    public function destroy($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $user = User::find($id);
                $user->delete();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $user->name . ' был уничтожен');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }
    public function profile(){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $role = DB::table('roles')->find($user->id)->name;
            $result = [
                'user' => $user,
                'role' => $role,
            ];
            return view('users.profile')->with('result', $result);
        }
    }
    public function profileUpdate(UserRequest $request, $id){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $editUser = User::find($id);
                $message = 'Данные успешно обновлены';
                if ($request->input('password') && $request->input('password_confirmation')) {
                    if ($request->input('password') === $request->input('password_confirmation')) {
                        $editUser->password = Hash::make($request->input('password'));
                        $message = 'Данные и пароль успешно обновлены';
                    } else {
                        return redirect()->route('users.profile')->with('warning', 'Пароли отличаются!');
                    }
                }
                $editUser->name = $request->input('name');
                $editUser->email = $request->input('email');
                $editUser->description = $request->input('description');
                $editUser->save();
                return redirect()->route('users.profile')->with('user', $user)->with('success', $message);
            }
        }
    }

    public function profileImageUpload(Request $request, $id){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                if($request->file('image')){
                    $path = $request->file('image')->store('profiles', 'public');
                    $editUser = User::where('id', $id)->first();
                    if($editUser->photo !== 'profiles/default.png') {
                        Storage::disk('public')->delete($editUser->photo);
                    }
                    $editUser->photo = $path;
                    $editUser->save();
                    return redirect()->route('users.profile')->with('user', $user);
                } else {
                    return redirect()->route('users.profile')->with('user', $user)->with('warning', 'Фото не выбрано');
                }

            }
        }
    }

    public function profileImageDelete($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $message = 'Фото профиля удалено';
                $editUser = User::find($id);
                if($editUser->photo !== 'profiles/default.png'){
                    Storage::disk('public')->delete($editUser->photo);
                    $editUser->photo = 'profiles/default.png';
                    $editUser->save();
                } else {
                    $message = 'Фото профиля не задано';
                }
                return redirect()->route('users.profile')->with('user', $user)->with('success', $message);
            }
        }
    }

    public function rolesCreateView()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            //if ($user->hasRole('SuperAdmin')) {
                $roles = Role::all();
                $permissions = Permission::all();
                return view('users.roles-create-view', ['roles' => $roles, 'permissions' => $permissions]);
            //}
        }
    }

    public function rolesCreateForm(Request $req, $typeAction)
    {
        //dd($req->input());
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $message = ''; $typeMessage = 'success';
                if ($typeAction == 'role') {
                    // $validated = $req->validate([
                    //     'permissions' => 'required',
                    //     'roles' => 'required',
                    // ]);
                    $role = Role::create(['name' => $req->input('role')]);
                    $message = 'Роль '. $role->name. ' успешно создана. ';
                    if($req->input('permissions')) {
                        $message .= 'Роли даны полномочия: ';
                        foreach ($req->input('permissions') as $permission) {
                            $role->givePermissionTo($permission);
                            $message .= $permission.', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                } else if ($typeAction == 'permission') {
                    $permission = Permission::create(['name' => $req->input('permission')]);
                    $roles = Role::all();
                    $message = 'Полномочие '. $permission->name. ' успешно создано. ';
                    if($req->input('roles') ) {
                        $message .= 'Полномочие присвоено следующим ролям: ';
                        foreach ($roles as $role) {
                            foreach ($req->input('roles') as $r) {
                                if ($role->name == $r) {
                                    $role->givePermissionTo($req->input('permission'));
                                    $message .= $role->name.', ';
                                }
                            }
                        }
                        $message = substr($message, 0, -2);
                    }
                }
                return redirect()->route('users.roles-create-view')->with($typeMessage, $message);
            }
        }
    }

    public function assignRole(Request $req)
    {
        //todo:
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            //if ($user->hasRole('SuperAdmin')) {

                $message = ''; $typeMessage = 'success';
                $message = 'Роль '. $req->input('role'). ' выдана следующим пользователям: ';
                foreach ($req->input('users') as $userId) {
                    $user = User::where('name', $userId)->first();
                    //dd($user);
                    $user->assignRole($req->input('role'));
                    $message .= $userId.', ';
                }
                $message = substr($message, 0, -2);
                return redirect()->route('users.roles-create-view')->with($typeMessage, $message);
            //}
        }
    }

    public function deleteRole($id)
    {
        Role::where('id', $id)->delete();
        return redirect()->route('users.roles-create-view');
    }

    public function deletePermission($id)
    {
        Permission::where('id', $id)->delete();
        return redirect()->route('users.roles-create-view');
    }

    public function usersList()
    {
        $users = User::get();
        return response()->json($users);
    }
}
