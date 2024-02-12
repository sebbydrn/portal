<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
    	$contacts = $this->contacts();
        return view('auth.passwords.email')->with(compact('contacts'));
    }
}
