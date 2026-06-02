  <!-- General JS Scripts -->
  <script src="<?= base_url('assets/modules/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/modules/popper.js') ?>"></script>
  <script src="<?= base_url('assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>

  <!-- Firebase FCM Implementation -->
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

  <script>
    const firebaseConfig = {
      apiKey: "AIzaSyB-2VsVBZ1ayN4Qj2Z1bvGSjlzeVasNu8A",
      projectId: "pasangin-c8050",
      messagingSenderId: "1016256565116",
      appId: "1:1016256565116:web:574dc80f84ac3dd2d05ef9"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Ambil token jika izin sudah diberikan sebelumnya
    messaging.getToken({
      vapidKey: 'BOZaLfwpGedtEE1EmKYvscUvpciGC0eSw09Z6ro9CAwkiFT14_N2hlfkg2eKkx6CA6IA3FJ44nuFgmi3UKRF31g'
    }).then((currentToken) => {
      if (currentToken) {
        console.log('FCM Token captured:', currentToken);
        document.getElementById('fcm_token').value = currentToken;
      }
    }).catch((err) => {
      console.log('FCM Token error:', err);
    });
  </script>
