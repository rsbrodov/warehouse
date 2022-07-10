<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
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
            return view('users.create');
        }
    }

    public function store(UserRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $new_user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => 'MODERATED',
                'parent_id' => Auth::id()
            ]);
            if ($user->hasRole('SuperAdmin')) {
                $new_user->assignRole('Admin'); //назначить роль юзеру
            } else if ($user->hasRole('Admin')) {
                $new_user->assignRole($request->input('role')); //назначить роль юзеру
            }
            return redirect()->route('users.index')->with('success', 'Пользователь ' . $new_user->name . ' успешно добавлен');
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
                $edit_user = User::where('id', $id)->first();
                $edit_user->name = $request->input('name');
                $edit_user->email = $request->input('email');
                $edit_user->save();
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $edit_user->name . ' успешно отредактирован');
            }
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
                return redirect()->route('users.index')->with('success', 'Пользователь ' . $act_user->name . ' активирован!');
            }
        }
    }

    public function block($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $del_user = User::where('id', $id)->first();
                $message = ''; $type_message = 'success';
                if ($del_user->status == 'BLOCKED') {
                    $del_user->status = 'MODERATED';
                    $message = 'Пользователь ' . $del_user->name . ' разблокирован и находится на модерации';
                } else {
                    $del_user->status = 'BLOCKED';
                    $message = 'Пользователь ' . $del_user->name . ' заблокирован!';
                    $type_message = 'warning';
                }
                $del_user->save();
                return redirect()->route('users.index')->with($type_message, $message);
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
                $message = ''; $type_message = 'success';
                if ($del_user->status == 'DELETED') {
                    $del_user->status = 'MODERATED';
                    $message = 'Пользователь ' . $del_user->name . ' восстановлен и находится на модерации';
                } else {
                    $del_user->status = 'DELETED';
                    $message = 'Пользователь ' . $del_user->name . ' удален!';
                    $type_message = 'info';
                }
                $del_user->save();
                return redirect()->route('users.index')->with($type_message, $message);
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
            //dd($user);
            return view('users.profile')->with('user', $user);
        }
    }
    public function profileUpdate(UserRequest $request, $id){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_user = User::where('id', $id)->first();
                $edit_user->name = $request->input('name');
                $edit_user->email = $request->input('email');
                $edit_user->save();
                return redirect()->route('users.profile')->with('user', $user);
            }
        }
    }
    public function profileImageUpload(Request $request, $id){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $path = $request->file('image')->store('profiles', 'public');
                $edit_user = User::where('id', $id)->first();
                Storage::disk('public')->delete($edit_user->photo);
                $edit_user->photo = $path;
                $edit_user->save();
                return redirect()->route('users.profile')->with('user', $user);
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

    public function rolesCreateForm(Request $req, $type_action)
    {
        //dd($req->input());
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $message = ''; $type_message = 'success';
                if ($type_action == 'role') {
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
                } else if ($type_action == 'permission') {
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
                return redirect()->route('users.roles-create-view')->with($type_message, $message);
            }
        }
    }

    public function assignRole(Request $req)
    {
        //todo:
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            //if ($user->hasRole('SuperAdmin')) {
                
                $message = ''; $type_message = 'success';
                $message = 'Роль '. $req->input('role'). ' выдана следующим пользователям: ';
                foreach ($req->input('users') as $user_id) {
                    $user = User::where('name', $user_id)->first();
                    //dd($user);
                    $user->assignRole($req->input('role'));
                    $message .= $user_id.', ';
                }
                $message = substr($message, 0, -2);
                return redirect()->route('users.roles-create-view')->with($type_message, $message);
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
