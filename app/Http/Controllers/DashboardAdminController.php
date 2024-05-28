<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardAdminController extends Controller
{
  public function index(){
    $q="  SELECT SUM(hmm.JUMLAH_MOBIL) as JUMLAH_MOBIL
                  , SUM(hmm.JUMLAH_SEWA) as JUMLAH_SEWA
                  , SUM(hmm.JUMLAH_AKTIF) as JUMLAH_AKTIF
                  , SUM(hmm.JUMLAH_NON_AKTIF) as JUMLAH_NON_AKTIF
          FROM(
            SELECT COUNT(m.id) as JUMLAH_MOBIL, 0 as JUMLAH_SEWA, 0 as JUMLAH_AKTIF, 0 as JUMLAH_NON_AKTIF
            FROM mst_mobil as m
            UNION ALL
            SELECT 0 as JUMLAH_MOBIL, COUNT(t.id) as JUMLAH_SEWA, 0 as JUMLAH_AKTIF, 0 as JUMLAH_NON_AKTIF
            FROM trx_transaction as t
            WHERE t.status = '003' 
            UNION ALL
            SELECT 0 as JUMLAH_MOBIL, 0 as JUMLAH_SEWA, COUNT(m.id) as JUMLAH_AKTIF, 0 as JUMLAH_NON_AKTIF
            FROM mst_mobil as m
            WHERE m.status = '001'
            UNION ALL 
            SELECT 0 as JUMLAH_MOBIL, 0 as JUMLAH_SEWA, 0 as JUMLAH_AKTIF, COUNT(m.id) as JUMLAH_NON_AKTIF
            FROM mst_mobil as m
            WHERE m.status = '002'
          )hmm";
    $data = collect(DB::select($q))->first();

    return view('dashboard_admin',compact('data'));
  }

  public function getDataDetail(Request $r){
    $q = "SELECT t.id
                  , CASE WHEN t.start_date IS NULL THEN '' ELSE DATE_FORMAT(t.start_date,'%Y-%m-%d') END as tanggal_pinjam
                  , CASE WHEN t.end_date IS NULL THEN '' ELSE DATE_FORMAT(t.end_date,'%Y-%m-%d') END as tanggal_kembali
                  , CASE WHEN t.kilometer_awal IS NULL THEN '' ELSE t.kilometer_awal END as kilometer_awal
                  , CASE WHEN t.kilometer_akhir IS NULL THEN '' ELSE t.kilometer_akhir END as kilometer_akhir
                  , t.total_hari
                  , u.name
                  , s.description as ket_status
          FROM trx_transaction as t
          INNER JOIN users as u ON u.id = t.id_user
          INNER JOIN mst_status as s ON s.id = t.status
          WHERE t.id_mobil = '" .$r->id_mobil. "'
          ORDER BY start_date DESC";

    $data = DB::select($q);
    return $data;
  }

  public function getData(Request $r){
    if($r->flag == "0"){
      $q = "SELECT CASE WHEN t.id IS NULL THEN null ELSE t.id END as id
                  , CASE WHEN t.start_date IS NULL THEN '' ELSE DATE_FORMAT(t.start_date,'%Y-%m-%d') END as tanggal_pinjam
                  , CASE WHEN t.end_date IS NULL THEN '' ELSE DATE_FORMAT(t.end_date,'%Y-%m-%d') END as tanggal_kembali
                  , m.nama
                  , m.plat
                  , s.description as ket_status
                  , CASE WHEN t.total_hari IS NULL THEN '' ELSE t.total_hari END as jumlah_hari
                  , t.id_mobil
                  , k.description as ket_merk
									, l.description as ket_model
                  , m.tarif
                  , CASE 
                        WHEN t.kilometer_akhir IS NULL AND t.kilometer_awal IS NULL THEN '0'
                        WHEN t.kilometer_akhir IS NULL THEN t.kilometer_awal 
                        ELSE t.kilometer_akhir 
                        END as kilometer_akhir
          FROM mst_mobil as m
          LEFT JOIN trx_transaction as t ON t.id_mobil = m.id AND t.status IN ('003','001')
          INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
          INNER JOIN mst_status as s ON s.id = m.status
          WHERE m.status IN ('003','001')";
    }

    if($r->flag == "1"){
      $q = "SELECT t.id
                  , DATE_FORMAT(t.start_date,'%Y-%m-%d') as tanggal_pinjam
                  , DATE_FORMAT(t.end_date,'%Y-%m-%d') as tanggal_kembali
                  , m.nama
                  , m.plat
                  , s.description as ket_status
                  , t.total_hari as jumlah_hari
                  , t.id_mobil
                  , k.description as ket_merk
									, l.description as ket_model
                  , m.tarif
                  , CASE 
                        WHEN t.kilometer_akhir IS NULL AND t.kilometer_awal IS NULL THEN '0'
                        WHEN t.kilometer_akhir IS NULL THEN t.kilometer_awal 
                        ELSE t.kilometer_akhir 
                        END as kilometer_akhir
          FROM trx_transaction as t
          INNER JOIN mst_mobil as m ON m.id = t.id_mobil
          INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
          INNER JOIN mst_status as s ON s.id = m.status
          WHERE t.status = '003'";
    }

    if($r->flag == "2"){
      $q = "SELECT '' as id
                  , '' as tanggal_pinjam
                  , '' as tanggal_kembali
                  , m.nama
                  , m.plat
                  , s.description as ket_status
                  , '' as jumlah_hari
                  , m.id as id_mobil
                  , k.description as ket_merk
									, l.description as ket_model
                  , m.tarif
                  , CASE WHEN km.kilometer_akhir IS NULL THEN '0' ELSE km.kilometer_akhir END as kilometer_akhir
          FROM mst_mobil as m
          LEFT JOIN(
              SELECT CASE WHEN t.kilometer_akhir IS NULL THEN t.kilometer_awal ELSE t.kilometer_akhir END as kilometer_akhir, t.id_mobil
              FROM trx_transaction as t
              ORDER BY t.end_date DESC
              LIMIT 1
            )km
            ON km.id_mobil = m.id
          INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
          INNER JOIN mst_status as s ON s.id = m.status
          WHERE m.status = '001'";
    }

    if($r->flag == "3"){
      $q = "SELECT '' as id
                  , '' as tanggal_pinjam
                  , '' as tanggal_kembali
                  , m.nama
                  , m.plat
                  , s.description as ket_status
                  , '' as jumlah_hari
                  , m.id as id_mobil
                  , k.description as ket_merk
									, l.description as ket_model
                  , m.tarif
                  , km.kilometer_akhir
          FROM mst_mobil as m
          LEFT JOIN(
              SELECT CASE WHEN t.kilometer_akhir IS NULL THEN t.kilometer_awal ELSE t.kilometer_akhir END as kilometer_akhir, t.id_mobil
              FROM trx_transaction as t
              ORDER BY t.end_date DESC
              LIMIT 1
            )km
            ON km.id_mobil = m.id
          LEFT JOIN trx_transaction as t ON t.id_mobil = m.id
          INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
          INNER JOIN mst_status as s ON s.id = m.status
          WHERE m.status = '002'";
    }

    $data = DB::select($q);
    return $data;
  }

  public function addTransaction(Request $r){
    $q = "SELECT t.id
					FROM trx_transaction as t
          INNER JOIN mst_mobil as m ON m.id = t.id_mobil
					WHERE t.id_mobil = '".$r->id_mobil."' AND m.plat = '".$r->plat."'";
		$data = DB::select($q);

		if($data){
      try{
        date_default_timezone_set('Asia/Jakarta');
        DB::BeginTransaction();

        DB::table('trx_transaction')
        ->where('id', $r->id_trans)
        ->update([
          'kilometer_akhir' => $r->jumlah_km,
          'status'          => '004',
          'updated_at'      => Date("Y-m-d H:i:s")
        ]);
  
        DB::table('mst_mobil')
        ->where('id', $r->id_mobil)
        ->update([
          'status'      => '001',
        ]);
  
        DB::Commit();
  
        return "";
      }catch(\Exception $e){
        DB::rollback();     
        return "Ada Kesalahan! <br/> Tolong kontak IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
      }

			return "";
		}else{
      return "Maaf, Plat mobil tidak sesuai.";
    }
  }
}
