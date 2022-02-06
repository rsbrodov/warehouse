<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class YurkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function linkHandler($data)
    {
        $status = [
            'moderated',
            'activated',
            'deleted'
        ];
        $users = User::all()->where('link', $data)->first();;
        $users->status = $status[1];
        $users->save();
        return redirect()->route('home')->with('success', 'Ваш email был подтверждён, ваш статус: ' . $users->status);
    }

    public function userCreateView()
    {
        if(Auth::check()){
            $user = Auth::user();
            //$user->assignRole('SuperAdmin');
            if($user->hasRole('SuperAdmin')){
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::all();
                return view('yurk.user-create-view', ['user_id' => $user_id, 'user_login' => $user->name, 'users' => $users]);
            }
        }else{
            print_r('Авторизируйтесь');
        }
    }

    public function userCreateForm(Request $req)
    {
        $users = new User;
        $users->name = $req->input('name');
        $users->email = $req->input('email');
        $users->parent_id = Auth::id();
        $users->status = 'MODERATED';
        //$users->save();
        return redirect()->route('home')->with('success', 'Пользователь ' . $users->name . ' был добавлен');
    }

    public function index2()
    {
        return view('home2');
    }
}
