<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class RegisterController extends Controller
{
  public function register()
  {
    return view('register');
  }
  
  public function actionregister(Request $request)
  {
    $getNoSIM = DB::table('users')
                ->where('no_sim','=',$request->no_sim)
                ->first();
    if($getNoSIM){
      Session::flash('message', 'Register Gagal. No SIM yang Anda inputkan sudah ada.');
      return redirect('register');
    }

    if(substr($request->no_telepon,2) != "62"){
      Session::flash('message', 'Register Gagal. No telepon salah format!');
      return redirect('register');
    }

    $user = User::create([
        'name' => $request->nama,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role' => 'GST',
        'created_at' => Date('Y-m-d H:i:s'),
        'password2' => $request->password,
        'alamat' => $request->alamat,
        'no_sim' => $request->no_sim,
        'no_telepon' => $request->no_telepon,
        'updated_at' => Date('Y-m-d H:i:s')
    ]);

    Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan username dan password.');
    return redirect('register');
  }
}
