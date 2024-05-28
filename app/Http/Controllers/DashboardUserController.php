<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardUserController extends Controller
{
  public function index(){
		return view('dashboard_user');
	}

	public function getDataMobil(Request $r){
		$q = "SELECT m.id
									, m.nama
									, k.description as ket_merk
									, l.description as ket_model
									, m.plat
									, m.tarif
					FROM mst_mobil as m
					INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
					WHERE m.id NOT IN (SELECT t.id_mobil
														 FROM trx_transaction as t
														 WHERE '".$r->tanggal." 00:00:00' BETWEEN t.start_date AND t.end_date) AND m.status <> '003'";
		$data = DB::select($q);
    return $data;
	}

	public function addTransaction(Request $r){
		date_default_timezone_set('Asia/Jakarta');
		$this->user_id = auth()->user()->id;

		$tanggal_kembali = date('Y-m-d', strtotime($r->tanggal_pinjam. ' + '.$r->jumlah_hari.' days'));
		$q = "SELECT m.id
									, m.nama
									, k.description as ket_merk
									, l.description as ket_model
									, m.plat
									, m.tarif
					FROM mst_mobil as m
					INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
					WHERE m.id IN (SELECT t.id_mobil
														 FROM trx_transaction as t
														 WHERE ('".$r->tanggal_pinjam." 00:00:00' BETWEEN t.start_date AND t.end_date OR '".$tanggal_kembali." 23:59:59' BETWEEN t.start_date AND t.end_date) AND t.id_mobil = '".$r->id_mobil."') AND m.status = '001'";

		$data = DB::select($q);

		if($data){
			return "Maaf, Mobil dan Tanggal yang Anda pilih sudah tidak dapat dipinjam/sewa.";
		}

		$q = "SELECT COUNT(*) as JUMLAH_DATA
					FROM trx_transaction
					WHERE DATE_FORMAT(created_at, '%Y-%m-%d') = '".Date('Y-m-d')."'";
		$data = DB::select($q);

		$ID_MOBIL = 1;
    if($data[0]){
      $ID_MOBIL = $data[0]->JUMLAH_DATA + 1;
    }else{
			$ID_MOBIL = 1;
		}

		$strIDMOBIL = "00" . $ID_MOBIL;
		$idTrans = Date('Y') . Date('m') . Date('d') . substr($strIDMOBIL,strlen($strIDMOBIL)-3,strlen($strIDMOBIL));

		try{
      DB::BeginTransaction();

      DB::table('trx_transaction')->insert([
        'id'              => $idTrans,
        'id_user'         => $this->user_id,
        'id_mobil'        => $r->id_mobil,
        'kilometer_awal'  => $r->jumlah_km,
        'start_date'      => $r->tanggal_pinjam,
        'end_date'        => $tanggal_kembali,
        'total_hari'      => $r->jumlah_hari,
        'status'          => '003',
        'created_at'      => Date('Y-m-d H:i:s'),
        'updated_at'      => null
      ]);

			DB::table('mst_mobil')
      ->where('id', $r->id_mobil)
      ->update([
        'status'      => '003',
      ]);

      DB::Commit();

      return "";
    }catch(\Exception $e){
      DB::rollback();     
      return "Ada Kesalahan! <br/> Tolong kontak IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }
	}
}
