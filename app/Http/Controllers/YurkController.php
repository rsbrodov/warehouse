<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class YurkController extends Controller
{
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
}
