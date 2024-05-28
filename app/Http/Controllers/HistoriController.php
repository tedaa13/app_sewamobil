<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HistoriController extends Controller
{
  public function index(){
		return view('histori_user');
	}

  public function getData(Request $r){
    $this->user_id = auth()->user()->id;

    $q = "SELECT t.id
                  , DATE_FORMAT(t.start_date,'%Y-%m-%d') as tanggal_pinjam
                  , DATE_FORMAT(t.end_date,'%Y-%m-%d') as tanggal_kembali
                  , m.nama
                  , m.plat
                  , s.description as ket_status
                  , t.total_hari as jumlah_hari
          FROM trx_transaction as t
          INNER JOIN mst_mobil as m ON m.id = t.id_mobil
          INNER JOIN mst_status as s ON s.id = t.status
          WHERE t.id_user = '".$this->user_id."'";

    $data = DB::select($q);
    return $data;
  }
}
