<div class="login-brand text-center">
   <h2 style="color: #FF5A5F; font-weight: bold;">Pasangin.</h2>
   <p class="text-muted" style="font-size: 12px;">Admin Panel</p>
</div>

<div class="card card-primary">
  <div class="card-header"><h4>Login</h4></div>

  <div class="card-body">
    <!-- Menampilkan Pesan Error/Gagal Login -->
    <?php if(session()->getFlashdata('error') || session()->getFlashdata('msg')):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?: session()->getFlashdata('msg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>

    <!-- Form Login -->
    <form method="POST" action="<?= site_url('admin/login') ?>" class="needs-validation" novalidate="">
      <input type="hidden" name="fcm_token" id="fcm_token">
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
