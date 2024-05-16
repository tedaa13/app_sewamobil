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
      url   	: "{{ url('histori') }}/getData",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblhistoriHTML').html('');
        $('#tblhistoriHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblhistori">'+
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
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblhistori').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.id+'</td>'+
            '<td>'+y.nama+'</td>'+
            '<td>'+y.plat+'</td>'+
            '<td>'+y.tanggal_pinjam+'</td>'+
            '<td>'+y.tanggal_kembali+'</td>'+
            '<td>'+y.jumlah_hari+'</td>'+
            '<td>'+y.ket_status+'</td>'+
          '</tr>'+
          '');
        
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblhistori').DataTable({
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
  <h1>Data Histori</h1>
  <div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblhistoriHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection