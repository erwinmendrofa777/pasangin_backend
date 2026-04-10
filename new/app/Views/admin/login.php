<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login Admin &mdash; Pasangin</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= base_url('assets/modules/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/modules/fontawesome/css/all.min.css') ?>">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
  
  <style>
      body { background-color: #f4f6f9; }
      .card-primary { border-top: 2px solid #FF5A5F; }
      .btn-primary { background-color: #FF5A5F; border-color: #FF5A5F; }
      .btn-primary:hover { background-color: #e04e53; border-color: #e04e53; }
      .login-brand { margin-bottom: 20px; }
  </style>
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            
            <div class="login-brand text-center">
               <h2 style="color: #FF5A5F; font-weight: bold;">Pasangin.</h2>
               <p class="text-muted" style="font-size: 12px;">Admin Panel</p>
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body">
                <!-- Menampilkan Pesan Error/Gagal Login -->
                <?php if(session()->getFlashdata('msg')):?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif;?>

                <!-- Form Login -->
                <form method="POST" action="<?= site_url('admin/login') ?>" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus placeholder="admin@pasangin.com">
                    <div class="invalid-feedback">
                      Email tidak boleh kosong
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required placeholder="******">
                    <div class="invalid-feedback">
                      Password tidak boleh kosong
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Masuk Dashboard
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer text-center mt-3">
              Copyright &copy; Pasangin 2024
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="<?= base_url('assets/modules/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/modules/popper.js') ?>"></script>
  <script src="<?= base_url('assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
</body>
</html>
