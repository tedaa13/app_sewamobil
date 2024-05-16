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
  });

  function searchIt(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('pengguna') }}/getData",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblPenggunaHTML').html('');
        $('#tblPenggunaHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblPengguna">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Username</th>'+
                '<th>Nama</th>'+        
                '<th>Alamat</th>'+
                '<th>No Telepon</th>'+
                '<th>No SIM</th>'+ 
                '<th>Role</th>'+     
                '<th>Created At</th>'+             
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblPengguna').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.username+'</td>'+
            '<td>'+y.name+'</td>'+
            '<td>'+y.alamat+'</td>'+
            '<td>'+y.no_telepon+'</td>'+
            '<td>'+y.no_sim+'</td>'+
            '<td>'+y.role+'</td>'+
            '<td>'+y.created_at+'</td>'+
          '</tr>'+
          '');
        
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblPengguna').DataTable({
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
  <h1>Data Pengguna</h1>
  <div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblPenggunaHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection