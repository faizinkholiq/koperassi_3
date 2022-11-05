<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Koperasi - Login</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="<?=base_url('assets/img/koperasi-logo.png') ?>" />
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/custom.css') ?>" rel="stylesheet">
    
</head>

<body class="">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center" style="margin-top:8rem">
            <div class="col-lg-7">
                <img style="width:40rem" src="<?=base_url('assets/img/login-vector.png')?>" />
            </div>
            <div class="col-xl-5 col-lg-5 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <div class="h4 row text-center" style="margin-bottom:3rem">
                                    <div class="col-lg-4 text-right">
                                        <img style="width:4.5rem" src="<?=base_url('assets/img/koperasi-logo.png')?>" />
                                    </div>
                                    <div class="my-text-primary text-left col-lg-6 font-weight-bold" style="font-size: 2rem">
                                        Sistem<br/>
                                        Koperasi
                                    </div>
                                </div>
                            </div>
                            <form class="user" method="POST" action="<?= site_url('user/login') ?>">
                                <div class="form-group">
                                    <input type="username" class="form-control form-control-user"
                                        id="usernameInput" aria-describedby="usernameHelp"
                                        placeholder="Enter Username..." name="username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user"
                                        id="passwordInput" placeholder="Password" name="password">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="customCheck">
                                        <label class="custom-control-label" for="customCheck">Remember
                                            Me</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn my-btn-primary btn-user btn-block">
                                    Login
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="register.html">Create an Account!</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

</body>

</html>