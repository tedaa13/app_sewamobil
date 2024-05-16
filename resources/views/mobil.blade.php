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
    searchIt();

    $('#save').on('click',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Anda yakin?",
        text: "Anda yakin untuk menambahkan data ini?",
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
            url         : "{{ url('/mobil') }}/addData",
            method      : "POST",
            data        : {
              "id_merk"     : document.getElementById("txtAddMerk").value,
              "id_model"    : document.getElementById("txtAddModel").value,
              "nama_mobil"  : document.getElementById("txtAddName").value,
              "no_plat"     : document.getElementById("txtAddPlat").value,
              "tarif"       : document.getElementById("txtAddTarif").value,
              "status"      : document.getElementById("txtAddStatus").value,
            },
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                $('#modal_addCar').modal('hide');
                searchIt();
                swal.fire("Sukses!","Data anda berhasil disimpan.","success");
              }
            }
          });
        }
      });
    });

    $('#update').on('click',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Anda yakin?",
        text: "Anda yakin untuk memperbaharui data ini?",
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
            url         : "{{ url('/mobil') }}/updateData",
            method      : "POST",
            data        : {
              "id_mobil"    : document.getElementById("txtEditID").value,
              "id_merk"     : document.getElementById("txtEditMerk").value,
              "id_model"    : document.getElementById("txtEditModel").value,
              "nama_mobil"  : document.getElementById("txtEditName").value,
              "no_plat"     : document.getElementById("txtEditPlat").value,
              "tarif"       : document.getElementById("txtEditTarif").value,
              "status"      : document.getElementById("txtEditStatus").value,
            },
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                $('#modal_editCar').modal('hide');
                searchIt();
                swal.fire("Sukses!","Data anda berhasil disimpan.","success");
              }
            }
          });
        }
      });
    });
  });

  function editIt($idMobil){
    $('#modal_editCar').modal('show');
    $.ajax({
      url         : "{{ url('/mobil') }}/getDataDetail",
      method      : "POST",
      data        : {
        "id_mobil"  : $idMobil
      },
      headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      success     : function (data) {
        console.log(data);
        document.getElementById("txtEditID").value        = $idMobil;
        document.getElementById("txtEditMerk").value  = data.id_merk;
        document.getElementById("txtEditModel").value = data.id_model;
        document.getElementById("txtEditName").value  = data.nama;
        document.getElementById("txtEditPlat").value  = data.plat;
        document.getElementById("txtEditTarif").value = numberWithCommas(data.tarif);
        document.getElementById("txtEditStatus").value    = data.status;
      }
    });
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function searchIt(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('mobil') }}/getData",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblMobilHTML').html('');
        $('#tblMobilHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblMobil">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Merk</th>'+
                '<th>Model</th>'+        
                '<th>Tipe</th>'+
                '<th>No Plat</th>'+
                '<th>Tarif</th>'+      
                '<th>Status</th>'+ 
                '<th>Created At</th>'+             
                '<th>-</th>'+   
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblMobil').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.ket_merk+'</td>'+
            '<td>'+y.ket_model+'</td>'+
            '<td>'+y.nama+'</td>'+
            '<td>'+y.plat+'</td>'+
            '<td>'+numberWithCommas(y.tarif)+'</td>'+
            '<td>'+y.ket_status+'</td>'+
            '<td>'+y.created_at+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editIt('+y.id+');"><i class="bi bi-pencil-square"></i></button></td>'+
          '</tr>'+
          '');
        
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblMobil').DataTable({
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
  <h1>Data Mobil</h1>
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_addCar">
        Tambah
      </button>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblMobilHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<div class="modal fade" id="modal_addCar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="txtAddMerk" class="col-sm-2 col-form-label">Merk</label>
            <select id="txtAddMerk" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_merek as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="txtAddModel" class="col-sm-2 col-form-label">Model</label>
            <select id="txtAddModel" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_model as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="txtAddName" class="col-sm-2 col-form-label">Tipe Mobil</label>
            <input type="text" class="form-control form-control-sm" id="txtAddName" placeholder="Stargazer Prime" required>
          </div>
          <div class="mb-3">
            <label for="txtAddPlat" class="col-sm-2 col-form-label">No. Plat</label>
            <input type="text" class="form-control form-control-sm" id="txtAddPlat" placeholder="B 1 IDN" required>
          </div>
          <div class="mb-3">
            <label for="txtAddTarif" class="col-sm-2 col-form-label">Tarif/Hari</label>
            <input type="text" class="form-control form-control-sm" id="txtAddTarif" placeholder="0" required>
          </div>
          <div class="mb-3">
            <label for="txtAddStatus" class="col-sm-2 col-form-label">Status</label>
            <select id="txtAddStatus" class="form-select form-select-sm" aria-label=".form-select-sm example">
              @foreach ($data_status as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Save</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_editCar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="text" id="txtEditID" hidden>
            <label for="txtEditMerk" class="col-sm-2 col-form-label">Merk</label>
            <select id="txtEditMerk" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_merek as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="txtEditModel" class="col-sm-2 col-form-label">Model</label>
            <select id="txtEditModel" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_model as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="txtEditName" class="col-sm-2 col-form-label">Tipe Mobil</label>
            <input type="text" class="form-control form-control-sm" id="txtEditName" placeholder="Stargazer Prime" required>
          </div>
          <div class="mb-3">
            <label for="txtEditPlat" class="col-sm-2 col-form-label">No. Plat</label>
            <input type="text" class="form-control form-control-sm" id="txtEditPlat" placeholder="B 1 IDN" required>
          </div>
          <div class="mb-3">
            <label for="txtEditTarif" class="col-sm-2 col-form-label">Tarif/Hari</label>
            <input type="text" class="form-control form-control-sm" id="txtEditTarif" placeholder="0" required>
          </div>
          <div class="mb-3">
            <label for="txtEditStatus" class="col-sm-2 col-form-label">Status</label>
            <select id="txtEditStatus" class="form-select form-select-sm" aria-label=".form-select-sm example">
              @foreach ($data_status as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update">Update</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>