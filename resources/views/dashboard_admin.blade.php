@extends('layouts.master')
@section('title', 'User')
<!-- section CSS -->
<style>
  .boldFont{
    font-weight: bold;
  }

  .textCenter{
    text-align: center;
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
    $('#kembali').on('click',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Anda yakin?",
        text: "Anda yakin untuk menyelesaikan mobil ini?",
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
            url         : "{{ url('/dashboard_admin') }}/addTransaction",
            method      : "POST",
            data        : {
              "id_mobil"  : document.getElementById("IDMOBIL").value,
              "plat"      : document.getElementById("no_plat").value,
            },
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                $('#modal_formkembali').modal('hide');
                location.reload();
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

  function kembalikkanIni($idtrans,$idMobil,$tipe_mobil,$nama_model,$nama_merk,$tarif,$plat,$tanggal_pinjam,$tanggal_kembali,$jumlah_hari){
    $('#modal_formkembali').modal('show');
    document.getElementById("IDMOBIL").value              = $idMobil;
    document.getElementById("tipe_mobil").innerHTML       = $tipe_mobil;
    document.getElementById("merk_mobil").innerHTML       = $nama_merk;
    document.getElementById("model_mobil").innerHTML      = $nama_model;
    document.getElementById("plat_mobil").innerHTML       = $plat;
    document.getElementById("tarif_mobil").innerHTML      = $tarif;
    document.getElementById("tanggal").innerHTML          = $tanggal_pinjam + " s/d " + $tanggal_kembali;
    document.getElementById("tarif_mobil").innerHTML      = numberWithCommas($tarif);
    document.getElementById("jumlah_hari").innerHTML      = $jumlah_hari;
    document.getElementById("total_biaya").innerHTML      = numberWithCommas($tarif * $jumlah_hari);
  }

  function searchIt($flag){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('dashboard_admin') }}/getData",
      data    : {
        "flag" : $flag,
      },
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tbldashboard_transactionHTML').html('');
        $('#tbldashboard_transactionHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tbldashboard_transaction">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>No Transaksi</th>'+
                '<th>Tipe Mobil</th>'+
                '<th>No Plat Mobil</th>'+   
                '<th>Tanggal Pinjam</th>'+
                '<th>Tanggal Kembali</th>'+ 
                '<th>Jumlah Hari</th>'+         
                '<th>Status</th>'+  
                '<th>Action</th>'+  
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          if(y.ket_status != 'Disewa'){
            $('#tbldashboard_transaction').append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.id+'</td>'+
              '<td>'+y.nama+'</td>'+
              '<td>'+y.plat+'</td>'+
              '<td>'+y.tanggal_pinjam+'</td>'+
              '<td>'+y.tanggal_kembali+'</td>'+
              '<td>'+y.jumlah_hari+'</td>'+
              '<td>'+y.ket_status+'</td>'+
              '<td></td>'+
            '</tr>'+
            '');
          }else{
            $('#tbldashboard_transaction').append(''+
            '<tr>'+
              '<td>'+i+'</td>'+
              '<td>'+y.id+'</td>'+
              '<td>'+y.nama+'</td>'+
              '<td>'+y.plat+'</td>'+
              '<td>'+y.tanggal_pinjam+'</td>'+
              '<td>'+y.tanggal_kembali+'</td>'+
              '<td>'+y.jumlah_hari+'</td>'+
              '<td>'+y.ket_status+'</td>'+
              '<td>'+
                '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="kembalikkanIni('+y.id+','+y.id_mobil+',\'' + y.nama + '\',\'' + y.ket_model + '\',\'' + y.ket_merk + '\',\'' + y.tarif + '\',\'' + y.plat + '\',\'' + y.tanggal_pinjam + '\',\'' + y.tanggal_kembali + '\',\'' + y.jumlah_hari + '\');">SUBMIT</button>'+
              '</td>'+
            '</tr>'+
            '');
          }
        
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tbldashboard_transaction').DataTable({
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
</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>Dashboard</h1>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <h5>Total Mobil</h5>
            </div>
            <div class="card-body textCenter" onclick="searchIt('0')" style="cursor:pointer;">
              <h1><?php echo $data->JUMLAH_MOBIL; ?> </h1>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <h5>Total Mobil Disewa</h5>
            </div>
            <div class="card-body textCenter" onclick="searchIt('1')" style="cursor:pointer;">
              <h1><?php echo $data->JUMLAH_SEWA; ?> </h1>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <h5>Total Mobil Aktif</h5>
            </div>
            <div class="card-body textCenter" onclick="searchIt('2')" style="cursor:pointer;">
              <h1><?php echo $data->JUMLAH_AKTIF; ?> </h1>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <h5>Total Mobil Non Aktif</h5>
            </div>
            <div class="card-body textCenter" onclick="searchIt('3')" style="cursor:pointer;">
              <h1><?php echo $data->JUMLAH_NON_AKTIF; ?> </h1>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tbldashboard_transactionHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<div class="modal fade" id="modal_formkembali" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
          <div class="col-sm-3"> Tanggal </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-8"> <span id="tanggal"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Tarif/hari </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="tarif_mobil"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Jumlah Hari </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="jumlah_hari"></span> </div>
        </div>
        <div class="row">
          <div class="col-sm-3"> Total Biaya </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <span id="total_biaya"></span> </div>
        </div>
        <hr></hr>
        <div class="row">
          <div class="col-sm-3"> No Plat </div>
          <div class="col-sm-1"> : </div>
          <div class="col-sm-5"> <input type="text" class="form-control" id="no_plat"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="kembali">Sewa</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>