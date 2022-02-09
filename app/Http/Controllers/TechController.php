<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class TechController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        //Role::create(['name' => 'Admin']);
        //Permission::create(['name' => 'read-component']);
        //$user= User::where('id', 1)->first();//запрос должен выглядеть именно так, если мы напишем get, то это как all() в Yii и у нас будет ошибка
        //$role = Role::findByName('SuperAdmin');
        //$role->givePermissionTo('create-component');
        if(Auth::check()){
            $user = Auth::user();
            //$user->assignRole('SuperAdmin');
            if($user->hasRole('SuperAdmin')){
                return 'autorized in tech';
            }
        }else{
            return 'not autorized';
        }
    }
    public function index()
    {
        return view('home');
    }
}
