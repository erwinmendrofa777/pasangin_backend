<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login Admin &mdash; Pasangin</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= base_url('favicon.ico?v=1') ?>" type="image/x-icon">

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
            
            <?= $this->include('App\Modules\Autentications\Views\components\_login_card') ?>
            <div class="simple-footer text-center mt-3">
              Copyright &copy; Pasangin 2024
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?= $this->include('App\Modules\Autentications\Views\components\_scripts') ?>
</body>
</html>
