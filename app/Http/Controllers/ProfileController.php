<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProfileController extends Controller
{
  public function index(){
    $this->user_id = auth()->user()->id;
    $q = "SELECT *
					FROM users
          WHERE id = '".$this->user_id."'";
		$data = DB::select($q);

    return view('profile',compact('data'));
  }
}
