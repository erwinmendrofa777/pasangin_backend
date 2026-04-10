<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Daftar Akun Supplier &mdash; Pasangin</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                        <div class="login-brand">
                             <img src="<?= base_url('assets/img/stisla-fill.svg') ?>" alt="logo" width="100" class="shadow-light rounded-circle">
                        </div>

                        <div class="card card-primary">
                            <div class="card-header"><h4>Daftar Akun Supplier</h4></div>

                            <div class="card-body">
                                <!-- Notifikasi Error Validasi -->
                                <?php if (session()->has('errors')) : ?>
                                    <div class="alert alert-danger">
                                        <ul class="m-0">
                                        <?php foreach (session('errors') as $error) : ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                        </ul>
                                    </div>
                                <?php endif ?>

                                <!-- PERBAIKAN DI SINI: Menggunakan route_to() -->
                                <form method="POST" action="<?= route_to('supplier.attempt.register') ?>">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label for="name">Nama Toko / Supplier</label>
                                        <input id="name" type="text" class="form-control" name="name" value="<?= old('name') ?>" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" value="<?= old('email') ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="password" class="d-block">Password</label>
                                            <input id="password" type="password" class="form-control" name="password" required>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="pass_confirm" class="d-block">Konfirmasi Password</label>
                                            <input id="pass_confirm" type="password" class="form-control" name="pass_confirm" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Daftar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                         <div class="mt-5 text-muted text-center">
                            <!-- PERBAIKAN DI SINI: Menggunakan route_to() -->
                            Sudah punya akun? <a href="<?= route_to('supplier.login') ?>">Login di sini</a>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; Pasangin <?= date('Y') ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
