@extends('layouts.master')
@section('title', 'Dashboard')
<!-- section CSS -->
<style>
  .boldFont{
    font-weight: bold;
  }

  .textCenter{
    text-align: center;
    vertical-align: middle;
  }

  .textRight{
    text-align: right;
    vertical-align: middle;
  }

  .textLeft{
    text-align: left;
    vertical-align: middle;
  }

  .frame {
    border: solid 1px grey;
    padding: 10px 10px 10px 10px;
    border-radius: 10px;
  }
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
  $(document).ready(function () {
    searchIt();

    $('#sewa').on('click',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Anda yakin?",
        text: "Anda yakin untuk meminjam/menyewa mobil ini?",
        icon: 'warning',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        allowOutsideClick: false
      }).then(function(x) {
        if(x.value === true){
          $.ajax({
            url         : "{{ url('/dashboard_user') }}/addTransaction",
            method      : "POST",
            data        : {
              "id_mobil"          : document.getElementById("IDMOBIL").value,
              "tanggal_pinjam"    : document.getElementById("tanggal_pinjam").value,
              "jumlah_hari"       : document.getElementById("jumlah_hari").value,
              "jumlah_km"         : document.getElementById("jumlah_km").value
            },
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                $('#modal_formsewa').modal('hide');
                searchIt();
                swal.fire("Sukses!","Data anda berhasil disimpan.","success");
              }
            }
          });
        }
      });
    });
  });

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function searchIt(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_user') }}/getDataMobil",
      data    : {
        "tanggal" : document.getElementById("start_date").value,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblMobilSewaHTML').html('');
        $('#tblMobilSewaHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblMobilSewa">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Tipe Mobil</th>'+
                '<th>Model Mobil</th>'+
                '<th>Tarif/hari</th>'+      
                '<th>Plat</th>'+      
                '<th>Action</th>'+             
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblMobilSewa').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.nama+'</td>'+
            '<td>'+y.ket_model+'</td>'+
            '<td>'+y.tarif+'</td>'+
            '<td>'+y.plat+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="sewaIni('+y.id+',\'' + y.nama + '\',\'' + y.ket_model + '\',\'' + y.ket_merk + '\',\'' + y.tarif + '\',\'' + y.plat + '\');">SEWA</button>'+
            '</td>'+
          '</tr>'+
          '');
        
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblMobilSewa').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function isNumber(evt){
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

     return true;
  }

  function sewaIni($idMobil,$tipe_mobil,$nama_model,$nama_merk,$tarif,$plat){
    $('#modal_formsewa').modal('show');
    document.getElementById("IDMOBIL").value              = $idMobil;
    document.getElementById("tipe_mobil").innerHTML       = $tipe_mobil;
    document.getElementById("merk_mobil").innerHTML       = $nama_merk;
    document.getElementById("model_mobil").innerHTML      = $nama_model;
    document.getElementById("plat_mobil").innerHTML       = $plat;
    document.getElementById("tarif_mobil").innerHTML      = numberWithCommas($tarif);
  }
  
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Daftar Mobil</h1>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-2">
          <input type="date" class="form-control" id="start_date" value="<?php echo Date('Y-m-d') ?>">
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-info" onclick="searchIt()"> Cari </button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblMobilSewaHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<div class="modal fade" id="modal_formsewa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Form Sewa Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
        <div class="row">
          <input type="text" class="form-control" id="IDMOBIL" hidden>
          <div class="col-sm-3"> Tipe Mobil </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="tipe_mobil"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Merk Mobil </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="merk_mobil"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Model Mobil </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="model_mobil"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> No Plat Mobil </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="plat_mobil"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Tarif/hari </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="tarif_mobil"></span> </div>
        </div>
        <hr></hr>
        <div class="row">
          <div class="col-sm-3"> Tanggal </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <input type="date" class="form-control" id="tanggal_pinjam" value="<?php echo Date('Y-m-d') ?>"> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Jumlah Hari </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <input type="number" class="form-control" id="jumlah_hari" value="1" min="1" max="7"> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Kilometer Awal </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <input type="text" class="form-control" id="jumlah_km" onkeypress="return isNumber(event)"> </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="sewa">Sewa</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>