<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function loginz(Request $request)
    {
        return 1234;
    }


}
