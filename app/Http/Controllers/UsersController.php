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
//    public function linkHandler($data)
//    {
//        $status = [
//            'moderated',
//            'activated',
//            'deleted'
//        ];
//        $users = User::all()->where('link', $data)->first();;
//        $users->status = $status[1];
//        $users->save();
//        return redirect()->route('home')->with('success', 'Ваш email был подтверждён, ваш статус: ' . $users->status);
//    }
    public function userCreateView()
    {
        //dd(Auth::id());
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin')) {
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::all();
                return view('users.user-create-view', ['user_id' => $user_id, 'user_login' => $user->name, 'users' => $users]);
            }
            if ($user->hasRole('Admin')) {
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::where('parent_id', Auth::id())->orWhere('id', Auth::id())->get();
                return view('users.user-create-view', ['user_id' => $user_id, 'user_login' => $user->name, 'users' => $users]);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userCreateForm(Request $req)
    {

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('SuperAdmin')) {
                $req->validate([
                    'password' => 'required|confirmed|min:6'
                ]);
                $new_user = User::create([
                    'name' => $req->input('name'),
                    'email' => $req->input('email'),
                    'password' => Hash::make($req->input('password')),
                    'status' => 'MODERATED',
                    'parent_id' => Auth::id()
                ]);
                $new_user->assignRole('Admin'); //назначить роль юзеру
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $new_user->name . ' был добавлен');
            } else if ($user->hasRole('Admin')) {
                //dd(Auth::id());
                $req->validate([
                    'password' => 'required|confirmed|min:6'
                ]);
                $new_user = User::create([
                    'name' => $req->input('name'),
                    'email' => $req->input('email'),
                    'password' => Hash::make($req->input('password')),
                    'status' => 'MODERATED',
                    'parent_id' => Auth::id()
                ]);
                $new_user->assignRole($req->input('role')); //назначить роль юзеру
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $new_user->name . ' был добавлен');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function rolesCreateView()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin')) {
                $roles = Role::all();
                    //$roles = Permission:: join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    //->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    //->get();
                //dd($roles);
                $permissions = Permission::all();
                return view('users.roles-create-view', ['roles' => $roles, 'permissions' => $permissions]);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function rolesCreateForm($type_action, Request $req)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin')) {
                if ($type_action == 'role') {
                    $role = Role::create(['name' => $req->input('role')]);
                } else if ($type_action == 'permission') {
                    $permission = Permission::create(['name' => $req->input('permission')]);
                }
                return view('users.roles-create-view');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userEditView($id)
    {
        //dd($id);
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_user = User::where('id', $id)->first();
                //dd($edit_user);
                return view('users.user-edit-view', ['edit_user' => $edit_user]); // вью - это физический файл, рут - это виртуальный путь, который ведет к методу
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userEditForm($id, Request $req)
    {
        //dd($id);
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_user = User::where('id', $id)->first();
                $edit_user->name = $req->input('name');
                $edit_user->email = $req->input('email');
                $edit_user->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $edit_user->name . ' был отредактирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userDeleteButton($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $del_user = User::where('id', $id)->first();
                if ($del_user->status == 'DELETED') {
                    $del_user->status = 'MODERATED';
                } else {
                    $del_user->status = 'DELETED';
                }
                $del_user->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $del_user->name . ' был удален');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userBlockButton($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $del_user = User::where('id', $id)->first();
                if ($del_user->status == 'BLOCKED') {
                    $del_user->status = 'MODERATED';
                } else {
                    $del_user->status = 'BLOCKED';
                }
                $del_user->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $del_user->name . ' был заблокирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    public function userActivateButton($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $act_user = User::where('id', $id)->first();
                if ($act_user->status == 'MODERATED') {
                    $act_user->status = 'ACTIVATED';
                }
                $act_user->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $act_user->name . ' был активирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

}
