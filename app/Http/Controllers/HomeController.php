<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function index2()
    {
        if(Auth::check()){
            $user = Auth::user();
            //$user->assignRole('SuperAdmin');
            if($user->hasRole('SuperAdmin')){
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::all();
                return view('users.user-create-view', ['user_id' => $user_id, 'user_login' => $user->name, 'users' => $users]);
            }
        }else{
            print_r('Авторизируйтесь');
        }
    }
}
