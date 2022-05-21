<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | E-Dashboard</title>

    <link href="https://rsiaaisyiyah.com/templates/ja_medicare/favicon.ico" rel="icon" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <style>
        input[type=text],
        input[type=date],
        input[type=password],
        input[type=search],
        .input-group-text {
            border-radius: 0 !important;
        }

        button,
        .card,
        .card-header,
        .info-box,
        .info-box-icon {
            border-radius: 0 !important;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{asset('dist/img/logo-rsia-new.png')}}" width="30%" alt="AdminLTE Logo"
                class="img-circle elevation-3"><br />
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{session('loginError')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <!-- <div class="alert alert-info text-center" role="alert">
                    <strong>Assalamualaikum, </strong> Silahkan login dengan akun user anda
                </div> -->
                <form class="" method="POST" action="/dms/login">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('id_user') is-invalid @enderror" id="id_user"
                            name="id_user" value="{{old('id_user')}}" required autofocus>
                        <label for="id_user">Username</label>
                        @error('id_user')
                        <div class="invalid-feedback">pesan eror</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Masukkan password" value="">
                        <label for="password">Password</label>
                        <div class="invalid-feedback">pesan eror</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block"><b class="fas fa-sign-in-alt"></b>
                                Masuk</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
</body>

</html>