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
        <div class="col-sm-4"> Nama</div>
        <div class="col-sm-1"> : </div>
        <div class="col-sm-7"> <?php echo $data[0]->name; ?> </div>
      </div>
      <div class="row">
        <div class="col-sm-4"> Username </div>
        <div class="col-sm-1"> : </div>
        <div class="col-sm-7"> <?php echo $data[0]->username; ?> </div>
      </div>
      <div class="row">
        <div class="col-sm-4"> Alamat </div>
        <div class="col-sm-1"> : </div>
        <div class="col-sm-7"> <?php echo $data[0]->alamat; ?> </div>
      </div>
      <div class="row">
        <div class="col-sm-4"> No Telepon </div>
        <div class="col-sm-1"> : </div>
        <div class="col-sm-7"> <?php echo $data[0]->no_telepon; ?> </div>
      </div>
      <div class="row">
        <div class="col-sm-4"> No SIM </div>
        <div class="col-sm-1"> : </div>
        <div class="col-sm-7"> <?php echo $data[0]->no_sim; ?> </div>
      </div>
    </div>
  </div>
</div>
@endsection