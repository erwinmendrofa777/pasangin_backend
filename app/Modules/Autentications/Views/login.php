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
  
  <!-- Google Fonts (Plus Jakarta Sans) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Animate.css & Typed.js CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  
  <style>
      :root {
          --primary-color: #FF5A5F;
          --primary-hover: #e04e53;
      }

      body {
          font-family: 'Plus Jakarta Sans', sans-serif;
          background: #FAFAFA; /* Dominant white background */
          min-height: 100vh;
          margin: 0;
          color: #2D3748;
          overflow-x: hidden; /* Mencegah munculnya scrollbar horizontal akibat animasi slide-in */
      }

      @media (min-width: 992px) {
          body {
              overflow: hidden; /* Hilangkan scrollbar vertikal di desktop agar pas 100vh */
          }
      }

      #app {
          width: 100%;
          padding: 0;
      }

      /* Glassmorphism card container */
      .card.card-primary {
          background: rgba(255, 255, 255, 0.75);
          backdrop-filter: blur(20px);
          -webkit-backdrop-filter: blur(20px);
          border: 1px solid rgba(255, 255, 255, 0.5);
          border-radius: 18px;
          box-shadow: 0 10px 35px 0 rgba(31, 38, 135, 0.08);
          overflow: hidden;
          transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      }

      .card.card-primary:hover {
          box-shadow: 0 15px 45px 0 rgba(31, 38, 135, 0.12);
          transform: translateY(-2px);
      }

      .card-primary {
          border-top: none !important; /* Remove Stisla's top border color styling */
          position: relative;
      }

      /* Decorative glowing gradient bar at the top of the card */
      .card-primary::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 4px;
          background: linear-gradient(90deg, var(--primary-color), #ff8a8f);
      }

      /* Form styling */
      .card .card-header {
          border-bottom: none !important;
          padding: 35px 40px 12px 40px !important;
          display: block;
      }

      .card .card-header h4 {
          font-size: 26px !important;
          font-weight: 700 !important;
          color: #2D3748 !important;
          margin: 0 !important;
      }

      .card .card-body {
          padding: 12px 40px 40px 40px !important;
      }

      .form-group {
          margin-bottom: 24px;
          position: relative;
      }

      .form-group label {
          font-weight: 600;
          font-size: 13px;
          letter-spacing: 0.3px;
          color: #4A5568;
          margin-bottom: 8px;
          text-transform: none;
      }

      .input-group-custom {
          position: relative;
          display: flex;
          align-items: center;
          width: 100%;
      }

      .input-icon {
          position: absolute;
          left: 16px;
          top: 50%;
          transform: translateY(-50%);
          color: #A0AEC0;
          z-index: 10;
          font-size: 15px;
          transition: all 0.3s ease;
          pointer-events: none;
      }

      .form-control-custom {
          width: 100%;
          padding: 14px 18px 14px 48px; /* Diperbesar untuk kenyamanan input */
          font-size: 15px;
          line-height: 1.5;
          color: #2D3748;
          background-color: rgba(255, 255, 255, 0.6) !important;
          border: 1.5px solid #E2E8F0;
          border-radius: 12px;
          transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
          height: auto;
      }

      .form-control-custom:focus {
          border-color: var(--primary-color) !important;
          background-color: #fff !important;
          box-shadow: 0 10px 25px -5px rgba(255, 90, 95, 0.15), 0 0 0 3px rgba(255, 90, 95, 0.1) !important;
          outline: none;
      }

      .form-control-custom:focus ~ .input-icon {
          color: var(--primary-color);
          transform: translateY(-50%) scale(1.1);
      }

      /* Chrome/Edge autofill style overrides to prevent default bright blue background override */
      input:-webkit-autofill,
      input:-webkit-autofill:hover,
      input:-webkit-autofill:focus,
      input:-webkit-autofill:active {
          -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.9) inset !important;
          -webkit-text-fill-color: #2D3748 !important;
          transition: background-color 5000s ease-in-out 0s;
      }

      /* Password toggle eye icon */
      .password-toggle {
          position: absolute;
          right: 16px;
          top: 50%;
          transform: translateY(-50%);
          cursor: pointer;
          color: #A0AEC0;
          z-index: 10;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 5px;
      }

      .password-toggle:hover {
          color: #4A5568;
          transform: translateY(-50%) scale(1.1);
      }

      /* Primary Button */
      .btn-primary-custom {
          background: linear-gradient(135deg, var(--primary-color) 0%, #ff7378 100%);
          border: none;
          color: white !important;
          font-weight: 700;
          font-size: 16px;
          letter-spacing: 0.5px;
          padding: 16px 28px; /* Diperbesar agar seimbang dengan ukuran card */
          border-radius: 12px;
          width: 100%;
          cursor: pointer;
          box-shadow: 0 4px 14px 0 rgba(255, 90, 95, 0.3);
          transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
          display: block;
          text-align: center;
      }

      .btn-primary-custom:hover {
          background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-color) 100%);
          box-shadow: 0 6px 20px 0 rgba(255, 90, 95, 0.4);
          transform: translateY(-1px);
          text-decoration: none;
      }

      .btn-primary-custom:active {
          transform: translateY(1px);
          box-shadow: 0 2px 8px 0 rgba(255, 90, 95, 0.3);
      }

      .btn-primary-custom:disabled {
          background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
          box-shadow: none;
          color: #718096 !important;
          cursor: not-allowed;
          transform: none;
      }

      /* Alert style customization */
      .alert {
          border-radius: 12px !important;
          border: none !important;
          font-weight: 500 !important;
          font-size: 14px !important;
          padding: 14px 20px !important;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
          margin-bottom: 20px !important;
          animation: fadeInDown 0.4s ease-out forwards !important;
      }
      
      .alert-danger, .alert-danger * {
          background-color: #FFF5F5 !important;
          color: #C53030 !important;
          border-left-color: #E53E3E !important;
      }
      
      .alert-danger {
          border-left: 4px solid #E53E3E !important;
      }

      .alert .close {
          padding: 14px 20px !important;
          color: #C53030 !important;
          opacity: 0.8 !important;
      }

      .alert .close:hover {
          opacity: 1 !important;
      }

      @keyframes fadeInDown {
          from {
              opacity: 0;
              transform: translateY(-10px);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      /* Entry Animations */
      @keyframes fadeInUp {
          from {
              opacity: 0;
              transform: translateY(35px) scale(0.96);
          }
          to {
              opacity: 1;
              transform: translateY(0) scale(1);
          }
      }

      .animate-fade-in-up {
          animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
          opacity: 0; /* starts transparent, animated in */
      }

      .delay-1 { animation-delay: 0.1s; }
      .delay-2 { animation-delay: 0.2s; }
      .delay-3 { animation-delay: 0.3s; }

      /* Split Screen Layout Styles */
      /* Custom Centered Container for Login */
      .login-container {
          width: 100%;
          margin-right: auto;
          margin-left: auto;
          padding-right: 15px;
          padding-left: 15px;
      }

      /* Split Screen Layout Styles */
      .brand-panel {
          padding: 40px 20px;
          min-height: 40vh;
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 2;
      }

      .login-panel {
          padding: 40px 20px;
          min-height: 60vh;
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 2;
      }

      @media (min-width: 992px) {
          .login-container {
              max-width: 1020px;
          }
          .brand-panel {
              min-height: 100vh;
              padding: 40px;
              justify-content: flex-end !important;
              padding-right: 50px !important;
              border-right: 1px solid rgba(255, 255, 255, 0.2);
          }
          .login-panel {
              min-height: 100vh;
              padding: 40px;
              justify-content: flex-start !important;
              padding-left: 50px !important;
          }
      }

      @media (min-width: 1200px) {
          .login-container {
              max-width: 1140px;
          }
          .brand-panel {
              padding-right: 60px !important;
          }
          .login-panel {
              padding-left: 60px !important;
          }
      }

      .brand-content-wrapper {
          max-width: 500px;
          width: 100%;
      }

      .login-content-wrapper {
          max-width: 420px;
          width: 100%;
      }

      /* Logo branding styling (Horizontal Modern Lockup & Cinematic Entrance) */
      .login-brand-custom {
          margin-bottom: 24px;
          display: flex;
          align-items: center;
          gap: 20px;
          cursor: default;
          position: relative;
      }

      .brand-logo-container {
          display: inline-block;
          position: relative;
          width: 64px;
          height: 64px;
          animation: logoEntrance 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
          transform-origin: center;
          transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }

      .login-brand-custom:hover .brand-logo-container {
          transform: scale(1.15) rotate(-10deg);
      }

      .brand-logo-img {
          width: 64px;
          height: 64px;
          object-fit: contain;
          position: relative;
          z-index: 2;
          animation: floatLogo 5s ease-in-out infinite 1.2s; /* starts floating after entrance finishes */
          transition: filter 0.3s ease;
      }

      .logo-shadow {
          position: absolute;
          bottom: -10px;
          left: 10%;
          width: 80%;
          height: 8px;
          background: rgba(255, 90, 95, 0.25) !important;
          border-radius: 50%;
          filter: blur(2px) !important;
          z-index: 1;
          animation: floatShadow 5s ease-in-out infinite 1.2s;
          pointer-events: none;
          transition: all 0.3s ease;
      }

      .login-brand-custom:hover .logo-shadow {
          background: rgba(255, 90, 95, 0.35) !important;
          filter: blur(3px) !important;
      }

      .brand-text-container {
          display: flex;
          flex-direction: column;
          align-items: flex-start;
          justify-content: center;
          animation: textReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
          opacity: 0;
          transform-origin: left center;
          overflow: hidden;
          white-space: nowrap;
          transition: transform 0.3s ease;
      }

      .login-brand-custom:hover .brand-text-container {
          transform: translateX(3px);
      }

      @media (max-width: 991px) {
          .login-brand-custom {
              justify-content: center;
              margin-bottom: 16px;
          }
          .brand-content-wrapper {
              text-align: center;
              display: flex;
              flex-direction: column;
              align-items: center;
          }
      }

      @keyframes logoEntrance {
          0% {
              opacity: 0;
              transform: scale(0.3) translateX(60px);
          }
          40% {
              opacity: 1;
              transform: scale(1.1) translateX(60px);
          }
          100% {
              opacity: 1;
              transform: scale(1) translateX(0);
          }
      }

      @keyframes textReveal {
          0% {
              opacity: 0;
              transform: translateX(-20px) scaleX(0.8);
              max-width: 0;
          }
          40% {
              opacity: 0;
              transform: translateX(-20px) scaleX(0.8);
              max-width: 0;
          }
          100% {
              opacity: 1;
              transform: translateX(0) scaleX(1);
              max-width: 320px;
          }
      }

      @keyframes floatLogo {
          0% {
              transform: translateY(0px) rotate(0deg);
          }
          50% {
              transform: translateY(-12px) rotate(3deg); /* bounce up slightly higher */
          }
          100% {
              transform: translateY(0px) rotate(0deg);
          }
      }

      @keyframes floatShadow {
          0% {
              transform: scale(1);
              opacity: 0.7;
              filter: blur(2px);
          }
          50% {
              transform: scale(0.6);
              opacity: 0.25;
              filter: blur(5px);
          }
          100% {
              transform: scale(1);
              opacity: 0.7;
              filter: blur(2px);
          }
      }
      
      .brand-title {
          font-size: 38px !important;
          font-weight: 800 !important;
          letter-spacing: -1px !important;
          margin: 0 !important;
          line-height: 1 !important;
          color: #2D3748 !important;
          transition: all 0.3s ease;
      }

      .brand-title span {
          color: var(--primary-color) !important;
          background: linear-gradient(135deg, #FF5A5F 0%, #ff8a8f 50%, #FF5A5F 100%);
          background-size: 200% auto;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          animation: shimmer 4s linear infinite;
      }

      @keyframes shimmer {
          to {
              background-position: 200% center;
          }
      }

      .brand-subtitle {
          font-size: 13px !important;
          font-weight: 700 !important;
          letter-spacing: 4px !important;
          text-transform: uppercase !important;
          color: #718096 !important;
          margin: 6px 0 0 0 !important;
          line-height: 1 !important;
      }

      /* Slogan (Typed.js) styling */
      .slogan-container {
          min-height: 52px;
          margin-top: 10px;
          text-align: left;
          animation: fadeInSlogan 1s ease-out 1.2s both; /* starts after header animates in */
          border-left: 3px solid var(--primary-color);
          padding-left: 12px;
      }

      @keyframes fadeInSlogan {
          from {
              opacity: 0;
              transform: translateY(10px);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      .typed-text {
          font-size: 15px; /* Disesuaikan sedikit agar pas dalam kontainer 500px */
          font-weight: 500;
          color: #4A5568;
          line-height: 1.6;
          display: inline;
      }
      
      @media (max-width: 991px) {
          .slogan-container {
              text-align: center;
              border-left: none;
              padding-left: 0;
          }
          .typed-text {
              border-top: 2px solid var(--primary-color);
              padding-top: 8px;
              display: inline-block;
          }
      }

      /* Typed.js cursor custom styling */
      .typed-cursor {
          color: var(--primary-color);
          font-size: 18px;
      }

      /* Footer styling */
      .simple-footer {
          font-size: 12px;
          color: #718096;
          font-weight: 500;
          margin-top: 24px !important;
      }

      /* Bootstrap validation override */
      .invalid-feedback {
          font-size: 12px;
          font-weight: 500;
          color: #E53E3E;
          margin-top: 6px;
          margin-left: 4px;
      }

      /* Glassmorphism background blobs */
      .blob-container {
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          height: 100vh;
          overflow: hidden;
          z-index: -1;
          pointer-events: none;
      }

      .blob {
          position: absolute;
          border-radius: 50%;
          filter: blur(80px);
          opacity: 0.55;
          animation: float 25s infinite alternate ease-in-out;
      }

      .blob-1 {
          width: 350px;
          height: 350px;
          background-color: rgba(255, 90, 95, 0.15); /* Aksen warna brand coral red lembut */
          top: -80px;
          left: -80px;
          animation-duration: 25s;
      }

      .blob-2 {
          width: 450px;
          height: 450px;
          background-color: rgba(255, 138, 143, 0.12); /* Aksen warna brand pink coral */
          bottom: -120px;
          right: -120px;
          animation-duration: 30s;
          animation-delay: -5s;
      }

      .blob-3 {
          width: 300px;
          height: 300px;
          background-color: rgba(255, 230, 230, 0.2); /* Aksen warna pink keputihan lembut */
          top: 30%;
          left: 40%;
          animation-duration: 22s;
          animation-delay: -10s;
      }

      @keyframes float {
          0% {
              transform: translate(0, 0) scale(1) rotate(0deg);
          }
          33% {
              transform: translate(40px, -60px) scale(1.15) rotate(120deg);
          }
          66% {
              transform: translate(-30px, 30px) scale(0.9) rotate(240deg);
          }
          100% {
              transform: translate(0, 0) scale(1) rotate(360deg);
          }
      }
  </style>
</head>

<body>
  <!-- Glowing Background Blobs -->
  <div class="blob-container">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      <div class="blob blob-3"></div>
  </div>

  <div id="app">
    <div class="login-container">
      <div class="row min-vh-100 g-0">
        
        <!-- Left Side: Branding and Info -->
        <div class="col-12 col-lg-6 brand-panel">
          <div class="brand-content-wrapper animate__animated animate__fadeInLeft">
            
            <div class="login-brand-custom">
               <div class="brand-logo-container">
                   <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Pasangin" class="brand-logo-img">
                   <div class="logo-shadow"></div>
               </div>
               <div class="brand-text-container">
                   <h2 class="brand-title">PASANG<span>IN.</span></h2>
                   <p class="brand-subtitle">Admin Panel</p>
               </div>
            </div>
            
            <!-- Dynamic Slogan (Typed.js) -->
            <div class="slogan-container">
                <span id="typed-slogan" class="typed-text"></span>
            </div>
            
          </div>
        </div>
        
        <!-- Right Side: Login Form -->
        <div class="col-12 col-lg-6 login-panel">
          <div class="login-content-wrapper animate__animated animate__fadeInRight">
            <?= $this->include('App\Modules\Autentications\Views\components\_login_card') ?>
            <div class="simple-footer text-center mt-4">
              Copyright &copy; Pasangin <?= date('Y') ?>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>

  <?= $this->include('App\Modules\Autentications\Views\components\_scripts') ?>

  <!-- Typed.js Slogan Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Typed !== 'undefined') {
            new Typed('#typed-slogan', {
                strings: [
                    'Solusi praktis bangun dan renovasi rumah impian Anda.',
                    'Manajemen proyek konstruksi transparan dan terpercaya.',
                    'Kelola tukang, material, dan progres proyek secara realtime.'
                ],
                typeSpeed: 50,
                backSpeed: 30,
                backDelay: 2500,
                startDelay: 1000,
                loop: true
            });
        }
    });
  </script>
</body>
</html>
