<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
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
   /*public function __construct()
    {
        $this->middleware('auth:web');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(Auth::guard('web')->check()) {
            return view('home');
            //return 'web auth';
        }elseif(Auth::guard('api')->check()) {
            return 'api auth';
        }else {
            return 'not auth';
        }
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
            return 'not auth';
        }
    }

    public function index3()
    {

        $dic = Dictionary::create(['code'=>'1523', 'name'=> 'namre', 'description'=> 'sdfsdrfsd', 'archive'=>0, 'created_author'=>1, 'updated_author'=>1]);
        $dictionary_element = DictionaryElement::create(['dictionary_id'=> $dic->id, 'value'=> 'sfsdf', 'created_author'=>1, 'updated_author'=>1]);
        //$user = User::create(['name'=>'admin', 'email'=> 'admin@mail.ru', 'pass'=> 'sdfsdrfsd', 'archive'=>0, 'created_author'=>1, 'updated_author'=>1]);
        print_r($dic);
        print_r($dictionary_element);
    }
}
