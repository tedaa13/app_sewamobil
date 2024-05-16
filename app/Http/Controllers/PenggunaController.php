<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PenggunaController extends Controller
{
  public function index(){
    return view('pengguna');
  }

  public function getData(Request $r){
    $q = "SELECT u.id 
                  , u.name 
                  , u.username 
                  , u.password  
                  , u.role 
                  , u.created_at 
                  , u.password2 
                  , CASE WHEN u.alamat IS NULL THEN '' ELSE u.alamat END as alamat
                  , CASE WHEN u.no_sim IS NULL THEN '' ELSE u.no_sim END as no_sim
                  , CASE WHEN u.no_telepon IS NULL THEN '' ELSE u.no_telepon END as no_telepon
          FROM users as u";

    $data = DB::select($q);
    return $data;
  }
}
