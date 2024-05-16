<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register Pengguna</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
</head>
<body>
  <div class="container"><br>
    <div class="col-md-6 col-md-offset-3">
      <h2 class="text-center">FORM REGISTER PENGGUNA</h3>
      <hr>
      @if(session('message'))
      <div class="alert alert-success">
          {{session('message')}}
      </div>
      @endif
      <form action="{{route('actionregister')}}" method="post">
      @csrf
        <div class="form-group">
            <label><i class="fa fa-child"></i> Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="" autocomplete="off">
        </div>
        <div class="form-group">
            <label><i class="fa fa-user"></i> Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" required="" autocomplete="off">
        </div>
        <div class="form-group">
            <label><i class="fa fa-home"></i> Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" required="" autocomplete="off">
        </div>
        <div class="form-group">
            <label><i class="fa fa-address-card"></i> No SIM</label>
            <input type="text" name="no_sim" id="no_sim" class="form-control" placeholder="327xxxx" required="" autocomplete="off">
        </div>
        <div class="form-group">
            <label><i class="fa fa-phone"></i> No Telepon</label>
            <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="62xxxx" required="" autocomplete="off">
        </div>
        <div class="form-group">
            <label><i class="fa fa-key"></i> Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary btn-block"> Register</button>
        <hr>
        <p class="text-center">Sudah punya akun silahkan <a href="/">Login Disini!</a></p>
      </form>
    </div>
  </div>
</body>
</html>