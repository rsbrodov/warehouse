<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $users = User::all();
            }
            if ($user->hasRole('Admin')) {
                $users = User::where('parent_id', Auth::id())->orWhere('id', Auth::id())->get();
            }
            return view('users.index')->with('users', $users);
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->check()) {
            return view('users.create');
        }
    }

    public function store(Request $req)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $req->validate([
                'name' => 'required',
                'email' => 'required|string|email|max:255'/*|unique:users,email,' . $user->id*/,
                'password' => 'required|confirmed|min:6'
            ]);
            $new_user = User::create([
                'name' => $req->input('name'),
                'email' => $req->input('email'),
                'password' => Hash::make($req->input('password')),
                'status' => 'MODERATED',
                'parent_id' => Auth::id()
            ]);
            if ($user->hasRole('SuperAdmin')) {
                $new_user->assignRole('Admin'); //назначить роль юзеру
            } else if ($user->hasRole('Admin')) {
                $new_user->assignRole($req->input('role')); //назначить роль юзеру
            }
            return redirect()->route('users.index')->with('success', 'Пользователь ' . $new_user->name . ' был добавлен');
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_user = User::where('id', $id)->first();
                return view('users.edit', ['edit_user' => $edit_user]); // вью - это физический файл, рут - это виртуальный путь, который ведет к методу
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('user', $user);
    }

    public function update(Request $req, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_user = User::where('id', $id)->first();
                $edit_user->name = $req->input('name');
                $edit_user->email = $req->input('email');
                $edit_user->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $edit_user->name . ' был отредактирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function activate($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $act_user = User::where('id', $id)->first();
                if ($act_user->status == 'MODERATED') {
                    $act_user->status = 'ACTIVATED';
                }
                $act_user->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $act_user->name . ' был активирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function block($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $del_user = User::where('id', $id)->first();
                if ($del_user->status == 'BLOCKED') {
                    $del_user->status = 'MODERATED';
                } else {
                    $del_user->status = 'BLOCKED';
                }
                $del_user->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $del_user->name . ' был заблокирован');
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
                $del_user = User::where('id', $id)->first();
                if ($del_user->status == 'DELETED') {
                    $del_user->status = 'MODERATED';
                } else {
                    $del_user->status = 'DELETED';
                }
                $del_user->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $del_user->name . ' был удален');
            }
        } else {
            print_r('Авторизируйтесь');
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


    public function rolesCreateView()
    {
        //dd(123);
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $roles = Role::all();
                $permissions = Permission::all();
                return view('users.roles-create-view', ['roles' => $roles, 'permissions' => $permissions]);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function rolesCreateForm(Request $req, $type_action)
    {
        //dd($req->input());
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                if ($type_action == 'role') {
                    $role = Role::create(['name' => $req->input('role')]);
                    foreach ($req->input('permissions') as $permission) {
                        $role->givePermissionTo($permission);
                    }
                } else if ($type_action == 'permission') {
                    //dd($req->input());
                    $permission = Permission::create(['name' => $req->input('permission')]);
                    $roles = Role::all();
                    //dd($roles);
                    foreach ($roles as $role) {
                        foreach ($req->input('roles') as $r) {
                            if ($role->name == $r) {
                                //print_r($role->name.' == '. $r.'</br>');
                                $role->givePermissionTo($req->input('permission'));
                            }
                        }
                    }
                }
                return redirect()->route('users.roles-create-view');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function assignRole(Request $req)
    {
        //todo:
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {

                foreach ($req->input('users') as $user_id) {
                    dd($user_id);
                    $users = User::where('id', $user_id)->first();
                    $users->assignRole($req->input('role'));
                }
                return redirect()->route('users.roles-create-view');
            }
        } else {
            print_r('Авторизируйтесь');
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
}
