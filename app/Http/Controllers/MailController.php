<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class MailController extends Controller
{
    public function send()
    {
        Mail::send(['text' => 'home'], ['name', 'test'], function ($message){
            $message->to('hsxcms@gmail.com', 'test')->subject('Test mail');
            $message->from('hsxcms@gmail.com', 'test');
        });
    }
}
