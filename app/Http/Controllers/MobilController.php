<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MobilController extends Controller
{
  public function index(){
    $q = "SELECT id, description
          FROM mst_merk_mobil
          ORDER BY description ASC";
    $data_merek = DB::select($q);

    $q = "SELECT id, description
          FROM mst_model_mobil
          ORDER BY description ASC";
    $data_model = DB::select($q);

    $q = "SELECT id, description
          FROM mst_status
          ORDER BY id ASC";
    $data_status = DB::select($q);

    return view('mobil',compact('data_merek','data_model','data_status'));
  }

  public function addData(Request $r){
    $this->user_id = auth()->user()->id;
    $this->role = auth()->user()->role;
    // dd($r->all());
    if($r->id_merk == "" || $r->id_model == "" || $r->nama_mobil == "" || $r->no_plat == "" || $r->tarif == "" || $r->status == ""){
      return "Semua data wajib diisi!";
    }

    $getPlat = DB::table('mst_mobil')
                ->where('plat','=',$r->no_plat)
                ->first();
    if($getPlat){
      return "No Plat sudah ada!";
    }

    $getID = DB::table('mst_mobil')
                ->orderby('id','desc')
                ->first();
    $ID_MOBIL = 1;
    if($getID){
      $ID_MOBIL = $getID->id + 1;
    }

    try{
      DB::BeginTransaction();

      DB::table('mst_mobil')->insert([
        'id'          => $ID_MOBIL,
        'nama'        => $r->nama_mobil,
        'merk'        => $r->id_merk,
        'model'       => $r->id_model,
        'plat'        => $r->no_plat,
        'tarif'       => $r->tarif,
        'status'      => $r->status,
        'created_at'  => Date('Y-m-d H:i:s'),
        'created_by'  => $this->user_id
      ]);

      DB::Commit();

      return "";
    }catch(\Exception $e){
      DB::rollback();     
      return "Ada Kesalahan! <br/> Tolong kontak IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }
  }

  public function getData(Request $r){
    $q = "SELECT m.id
                  , m.nama
                  , k.description as ket_merk
                  , l.description as ket_model
                  , m.plat
                  , m.tarif
                  , s.description as ket_status
                  , CASE WHEN CONVERT(m.created_at,VARCHAR(45)) IS NULL THEN '' ELSE CONVERT(m.created_at,VARCHAR(45)) END as created_at
          FROM mst_mobil as m
          INNER JOIN mst_merk_mobil as k ON k.id = m.merk
          INNER JOIN mst_model_mobil as l ON l.id = m.model
          INNER JOIN mst_status as s ON s.id = m.status";

    $data = DB::select($q);
    return $data;
  }

  public function getDataDetail(Request $r){
    $q = "SELECT m.id
                  , m.merk as id_merk
                  , m.model as id_model
                  , m.nama
                  , m.tarif
                  , m.plat
                  , m.status
          FROM mst_mobil as m
          WHERE m.id = '".$r->id_mobil ."'";

    $data = collect(DB::select($q))->first();
    return $data;
  }

  public function updateData(Request $r){
    $this->user_id = auth()->user()->id;
    $this->role = auth()->user()->role;
    
    $getPlat = DB::table('mst_mobil')
                ->where('plat','=',$r->no_plat)
                ->where('id','<>',$r->id_mobil)
                ->first();
    if($getPlat){
      return "No Plat sudah ada!";
    }

    try{
      DB::BeginTransaction();

      DB::table('mst_mobil')
      ->where('id', $r->id_mobil)
      ->update([
        'nama'        => $r->nama_mobil,
        'merk'        => $r->id_merk,
        'model'       => $r->id_model,
        'plat'        => $r->no_plat,
        'tarif'       => $r->tarif,
        'status'      => $r->status,
        'created_at'  => Date('Y-m-d H:i:s'),
        'created_by'  => $this->user_id
      ]);

      DB::Commit();

      return "";
    }catch(\Exception $e){
      DB::rollback();     
      return "Ada Kesalahan! <br/> Tolong kontak IT Admin. <br/> ERROR: <br/> [ '". substr($e,0,1000) ."' ]";
    }

  }

  public function getDataHistori(Request $r){
    $q = "SELECT t.id
                  , id_user
                  , id_mobil
                  , DATE_FORMAT(t.start_date,'%d-%m-%Y') as tanggal_pinjam
                  , DATE_FORMAT(t.end_date,'%d-%m-%Y') as tanggal_kembali
                  , u.name as nama_peminjam
                  , m.nama as nama_mobil
                  , CASE WHEN m.status = '003' THEN s.description ELSE 'Selesai' END as ket_status
          FROM trx_transaction as t
          INNER JOIn mst_mobil as m ON m.id = t.id_mobil
          INNER JOIN users as u ON u.id = t.id_user
          INNER JOIN mst_status as s ON s.id = m.status
          WHERE t.id_mobil = '".$r->id_mobil ."'";

    $data = DB::select($q);
    return $data;
  }
}
