<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::check()) {
            $user = Auth::user();
            //$user->assignRole('SuperAdmin'); //назначить роль юзеру
            if ($user->hasRole('SuperAdmin')) {
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::all();
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
                $users = new User;
                $users->name = $req->input('name');
                $users->email = $req->input('email');
                $users->parent_id = Auth::id();
                $users->status = 'MODERATED';
                //$users->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $users->name . ' был добавлен');
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
            if ($user->hasRole('SuperAdmin')) {
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
            if ($user->hasRole('SuperAdmin')) {
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
            if ($user->hasRole('SuperAdmin')) {
                $del_user = User::where('id', $id)->first();
                //dd($del_user->id, $del_user->status);
                $del_user->status = 'DELETED';
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
            if ($user->hasRole('SuperAdmin')) {
                $del_user = User::where('id', $id)->first();
                $del_user->status = 'BLOCKED';
                $del_user->save();
                return redirect()->route('users.user-create-view')->with('success', 'Пользователь ' . $del_user->name . ' был заблокирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }
    public function index2()
    {
        return view('home2');
    }
}
