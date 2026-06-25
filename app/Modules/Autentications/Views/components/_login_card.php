<div class="card card-primary">
  <div class="card-header">
    <h4>Login</h4>
  </div>

  <div class="card-body">
    <!-- Menampilkan Pesan Error/Gagal Login -->
    <?php if(session()->getFlashdata('error') || session()->getFlashdata('msg')):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
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
        <div class="input-group-custom">
          <input id="email" type="email" class="form-control-custom" name="email" tabindex="1" required autofocus placeholder="admin@pasangin.com">
          <i class="far fa-envelope input-icon"></i>
          <div class="invalid-feedback">
            Email tidak boleh kosong
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="control-label">Password</label>
        <div class="input-group-custom">
          <input id="password" type="password" class="form-control-custom" name="password" tabindex="2" required placeholder="******">
          <i class="fas fa-lock input-icon"></i>
          <span class="password-toggle" id="togglePassword">
            <i class="far fa-eye" id="togglePasswordIcon"></i>
          </span>
          <div class="invalid-feedback">
            Password tidak boleh kosong
          </div>
        </div>
      </div>

      <div class="form-group" style="margin-top: 30px; margin-bottom: 10px;">
        <button type="submit" class="btn-primary-custom" tabindex="4">
          Masuk Dashboard
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const icon = document.querySelector('#togglePasswordIcon');
    
    if (togglePassword && password && icon) {
        togglePassword.addEventListener('click', function () {
            // Toggle input type
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle eye icon class
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    }

    // AJAX Form Submit & Loading State
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    const cardBody = document.querySelector('.card-body');

    if (form && submitBtn && cardBody) {
        form.addEventListener('submit', function (e) {
            // Validasi bootstrap / native dijalankan terlebih dahulu
            if (!form.checkValidity()) {
                return;
            }

            // Hentikan submit normal
            e.preventDefault();

            // Ubah tombol ke state memuat
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghubungkan...';

            // Hapus alert error sebelumnya jika ada
            const existingAlert = cardBody.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Ambil data form
            const formData = new FormData(form);

            // Kirim request login menggunakan AJAX Fetch
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Terjadi kesalahan jaringan');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    submitBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Mengalihkan...';
                    window.location.href = data.redirect;
                } else {
                    // Buat alert baru berisi pesan kesalahan
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>${data.message}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;

                    // Tambahkan fungsi close secara eksplisit dan responsif
                    const closeBtn = alertDiv.querySelector('.close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function () {
                            if (window.jQuery) {
                                window.jQuery(alertDiv).fadeOut(function () {
                                    alertDiv.remove();
                                });
                            } else {
                                alertDiv.remove();
                            }
                        });
                    }

                    // Sisipkan alert di atas form
                    cardBody.insertBefore(alertDiv, form);

                    // Reset tombol submit
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Masuk Dashboard';
                }
            })
            .catch(error => {
                console.error('AJAX Login Error:', error);

                // Buat alert kesalahan sistem / koneksi
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>Terjadi kesalahan sistem atau koneksi. Silakan coba lagi.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;

                const closeBtn = alertDiv.querySelector('.close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function () {
                        if (window.jQuery) {
                            window.jQuery(alertDiv).fadeOut(function () {
                                alertDiv.remove();
                            });
                        } else {
                            alertDiv.remove();
                        }
                    });
                }

                cardBody.insertBefore(alertDiv, form);

                // Reset tombol submit
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Masuk Dashboard';
            });
        });
    }
});
</script>
