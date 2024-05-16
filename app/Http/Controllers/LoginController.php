<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class LoginController extends Controller
{
  public function login()
  {
    if (Auth::check()) {
        return redirect('home');
    }else{
        return view('login');
    }
  }

  public function actionlogin(Request $request)
  {
    // $hashedPassword = Hash::make('123456');
    // dd($hashedPassword);
    // dd($request->password);
    // dd($request->all());
    $data = [
        'username' => $request->input('username'),
        'password' => $request->input('password'),
    ];

    if (Auth::Attempt($data)) {
      $getRole = DB::table('users')
                    ->where('username','=',$data['username'])
                    ->first();
      if($getRole->role == 'GST'){
        return redirect('dashboard_user');
      }else{
        return redirect('dashboard_admin');
      }
    }else{
        Session::flash('error', 'Email atau Password Salah');
        return redirect('/');
    }
  }

  public function actionlogout()
  {
    Auth::logout();
    return redirect('/');
  }
}