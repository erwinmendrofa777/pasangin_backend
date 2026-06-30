<?php
// Ini adalah file layout utama (template master) untuk Stisla.
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title><?= $this->renderSection('title') ?> &mdash; Pasangin.co.id</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= base_url('favicon.ico?v=1') ?>" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

  <!-- GLightbox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
  <?= $this->renderSection('style') ?>

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

  <style>
    /* Overlay for mandatory notification */
    #notif-force-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.95);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      text-align: center;
      backdrop-filter: blur(8px);
    }

    .notif-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 90%;
    }

    .notif-box i {
      font-size: 60px;
      color: #6777ef;
      margin-bottom: 20px;
    }

    .notif-box h2 {
      font-weight: 800;
      color: #2d3748;
      margin-bottom: 15px;
    }

    .notif-box p {
      color: #718096;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .btn-notif {
      background: #6777ef;
      color: #fff;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 700;
      border: none;
      transition: 0.3s;
      cursor: pointer;
    }

    .btn-notif:hover {
      background: #394eea;
      transform: translateY(-2px);
    }

    .btn-notif:active {
      transform: translateY(0);
    }

    .denied-instruction {
      display: none;
      margin-top: 20px;
      padding: 15px;
      background: #fff5f5;
      border-radius: 10px;
      color: #c53030;
      font-size: 0.85rem;
      border: 1px solid #feb2b2;
    }

    /* Prevent double scrollbar conflicts on NiceScroll targets under Bootstrap 5 */
    .dropdown-list-icons::-webkit-scrollbar,
    .dropdown-list-message::-webkit-scrollbar,
    .chat-content::-webkit-scrollbar,
    #top-5-scroll::-webkit-scrollbar {
      display: none !important;
      width: 0 !important;
      height: 0 !important;
    }

    .dropdown-list-icons,
    .dropdown-list-message,
    .chat-content,
    #top-5-scroll {
      -ms-overflow-style: none !important;
      /* IE/Edge */
      scrollbar-width: none !important;
      /* Firefox */
    }

    /* Native smooth scroll for expanded sidebar */
    body:not(.sidebar-mini) .main-sidebar {
      overflow-y: auto !important;
      -ms-overflow-style: auto !important;
      /* Edge */
      scrollbar-width: thin !important;
      /* Firefox */
      scrollbar-color: rgba(0, 0, 0, 0.15) transparent !important;
    }

    /* Webkit scrollbar customization for expanded sidebar */
    body:not(.sidebar-mini) .main-sidebar::-webkit-scrollbar {
      display: block !important;
      width: 6px !important;
    }

    body:not(.sidebar-mini) .main-sidebar::-webkit-scrollbar-track {
      background: transparent !important;
    }

    body:not(.sidebar-mini) .main-sidebar::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1) !important;
      border-radius: 6px !important;
      transition: background 0.3s;
    }

    body:not(.sidebar-mini) .main-sidebar:hover::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.25) !important;
    }

    /* Enable scroll on mini sidebar, but allow tooltips to show */
    body.sidebar-mini .main-sidebar {
      position: fixed !important;
      height: 100vh !important;
      overflow-y: auto !important;
      -ms-overflow-style: auto !important;
      /* Edge */
      scrollbar-width: thin !important;
      /* Firefox */
      scrollbar-color: rgba(0, 0, 0, 0.15) transparent !important;
    }

    /* Webkit scrollbar customization for mini sidebar */
    body.sidebar-mini .main-sidebar::-webkit-scrollbar {
      display: block !important;
      width: 4px !important;
    }

    body.sidebar-mini .main-sidebar::-webkit-scrollbar-track {
      background: transparent !important;
    }

    body.sidebar-mini .main-sidebar::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1) !important;
      border-radius: 4px !important;
    }

    body.sidebar-mini .main-sidebar:hover::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.25) !important;
    }

    /* Position fly-out dropdown menus relative to viewport so they are not clipped by overflow-y: auto */
    body.sidebar-mini .main-sidebar .sidebar-menu>li:hover ul.dropdown-menu {
      display: block !important;
      position: fixed !important;
      top: auto !important;
      left: 65px !important;
      width: 200px !important;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06) !important;
      border: 1px solid #f1f5f9 !important;
      background-color: #fff !important;
    }

    .main-content {
      padding-top: 115px !important;
    }

    @media (max-width: 1024px) {
      .main-content {
        padding-top: 110px !important;
      }
    }

    /* ==========================================================================
       PREMIUM SIDEBAR & NAVBAR REDESIGN STYLE OVERRIDES
       ========================================================================== */

    /* General layout variables */
    :root {
      --sidebar-width: 260px;
      --navbar-height: 70px;
      --transition-smooth: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* --- SIDEBAR CONTAINER --- */
    .main-sidebar {
      width: var(--sidebar-width) !important;
      background: #ffffff !important;
      border-right: 1px solid #f1f5f9 !important;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.01) !important;
      transition: var(--transition-smooth) !important;
    }

    /* Brand Header - Expanded */
    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) {
      height: 75px !important;
      line-height: 75px !important;
      border-bottom: 1px solid #f8fafc;
      padding: 0 24px !important;
      display: flex !important;
      align-items: center;
      justify-content: flex-start;
      opacity: 1;
      visibility: visible;
      transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
        visibility 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) a {
      text-decoration: none !important;
      display: flex !important;
      align-items: center;
      gap: 12px;
      transition: var(--transition-smooth);
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) a:hover {
      transform: translateX(2px);
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) img {
      width: 38px !important;
      height: 38px !important;
      object-fit: contain;
      filter: drop-shadow(0 4px 6px rgba(229, 57, 53, 0.15));
      transition: transform 0.4s ease;
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) a:hover img {
      transform: rotate(-8deg) scale(1.05);
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) span {
      font-family: 'Outfit', 'Inter', sans-serif;
      font-size: 1.25rem !important;
      font-weight: 800 !important;
      letter-spacing: 0.5px;
      color: #1e293b !important;
    }

    .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) span .text-danger {
      color: var(--palette-primary, #e53935) !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-brand:not(.sidebar-brand-sm) {
      opacity: 0 !important;
      visibility: hidden !important;
      pointer-events: none;
    }

    /* Brand Header - Mini */
    .main-sidebar .sidebar-brand-sm {
      height: 75px !important;
      line-height: 75px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 0 !important;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      border-bottom: 1px solid #f8fafc;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
        visibility 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    .main-sidebar .sidebar-brand-sm a {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 100% !important;
      height: 100% !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-brand-sm {
      opacity: 1 !important;
      visibility: visible !important;
    }

    .main-sidebar .sidebar-brand-sm a img {
      width: 30px !important;
      height: 30px !important;
      transition: transform 0.3s ease;
    }

    .main-sidebar .sidebar-brand-sm a:hover img {
      transform: scale(1.1);
    }

    /* Menu Wrapper Scrollbar */
    #sidebar-wrapper {
      padding: 15px 12px 30px 12px !important;
    }

    /* Menu Headers */
    .sidebar-menu .menu-header {
      padding: 0 15px !important;
      margin-top: 24px !important;
      margin-bottom: 8px !important;
      font-size: 0.72rem !important;
      font-weight: 700 !important;
      color: #94a3b8 !important;
      letter-spacing: 0.12em !important;
      text-transform: uppercase !important;
      opacity: 0.85;
    }

    .sidebar-menu .menu-header:first-of-type {
      margin-top: 10px !important;
    }

    /* Menu Items List */
    .sidebar-menu li {
      margin-bottom: 4px !important;
    }

    .sidebar-menu li a {
      display: flex !important;
      align-items: center !important;
      padding: 11px 16px !important;
      height: auto !important;
      border-radius: 10px !important;
      font-size: 0.88rem !important;
      font-weight: 600 !important;
      color: #475569 !important;
      transition: var(--transition-smooth) !important;
      position: relative;
      background: transparent;
    }

    .sidebar-menu li a i {
      font-size: 1.05rem !important;
      width: 24px !important;
      margin-left: 0 !important;
      margin-right: 0 !important;
      padding: 0 !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      color: #64748b !important;
      transition: var(--transition-smooth) !important;
    }

    .sidebar-menu li a span {
      display: inline-block !important;
      opacity: 1;
      visibility: visible;
      white-space: nowrap;
      max-width: 180px;
      overflow: hidden;
      margin-left: 12px;
      transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
        max-width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
        margin-left 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
        visibility 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu li a span {
      max-width: 0 !important;
      opacity: 0 !important;
      visibility: hidden !important;
      margin-left: 0 !important;
    }

    /* Menu Items Hover */
    .sidebar-menu li a:hover {
      background: #f8fafc !important;
      color: var(--palette-primary, #e53935) !important;
    }

    .sidebar-menu li a:hover i {
      color: var(--palette-primary, #e53935) !important;
      transform: scale(1.1);
    }

    /* Menu Items Active State - apply ONLY to direct link (not submenus unless their parent li is active) */
    .sidebar-menu li.active>a {
      background: rgba(229, 57, 53, 0.07) !important;
      /* Premium brand alpha tint */
      color: var(--palette-primary, #e53935) !important;
      font-weight: 700 !important;
      box-shadow: none !important;
    }

    .sidebar-menu li.active>a i {
      color: var(--palette-primary, #e53935) !important;
    }

    /* Active Left Border Accent Indicator */
    .sidebar-menu li.active>a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 20%;
      bottom: 20%;
      width: 3.5px;
      background: var(--palette-primary, #e53935);
      border-radius: 0 4px 4px 0;
    }

    /* Dropdown Chevron Animation */
    .main-sidebar .sidebar-menu li a.has-dropdown::after {
      transition: transform 0.3s ease-in-out !important;
    }

    .main-sidebar .sidebar-menu li.active a.has-dropdown::after {
      transform: translate(0, -50%) rotate(-180deg) !important;
    }

    /* Collapsed Sidebar (sidebar-mini) refinements */
    body.sidebar-mini .main-sidebar {
      width: 65px !important;
    }

    body.sidebar-mini #sidebar-wrapper {
      padding: 15px 0 30px 0 !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu>li {
      padding: 2px 0 !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu>li.menu-header {
      display: none !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu li a {
      padding: 12px 0 !important;
      justify-content: center !important;
      border-radius: 10px !important;
      margin: 0 10px !important;
      width: auto !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu li.active a {
      background: rgba(229, 57, 53, 0.08) !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu li.active a::before {
      display: none !important;
    }

    /* Transition for main content layout on sidebar toggle */
    @media (min-width: 1025px) {
      .main-sidebar {
        position: fixed;
        height: 100vh;
      }

      .main-content,
      .main-footer {
        transition: var(--transition-smooth) !important;
      }
    }


    /* --- NAVBAR CONTAINER --- */
    .navbar-bg {
      display: none !important;
      /* Remove legacy background shape */
    }

    .main-navbar {
      height: var(--navbar-height) !important;
      left: calc(var(--sidebar-width) + 24px) !important;
      right: 24px !important;
      top: 20px !important;
      background: rgba(255, 255, 255, 0.45) !important;
      backdrop-filter: blur(24px) !important;
      -webkit-backdrop-filter: blur(24px) !important;
      border: 1px solid rgba(255, 255, 255, 0.6) !important;
      border-radius: 18px !important;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04) !important;
      padding: 24px 0 24px 24px !important;
      transition: var(--transition-smooth) !important;
      position: absolute !important;
      z-index: 890 !important;
    }

    body.sidebar-mini .main-navbar {
      left: calc(65px + 24px) !important;
    }

    /* Navbar items adjustments */
    .main-navbar .navbar-nav {
      align-items: center;
    }

    /* Menu toggle button icon styling */
    .main-navbar a.nav-link-lg {
      width: 44px !important;
      height: 44px !important;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5) !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      color: #475569 !important;
      transition: var(--transition-smooth);
      padding: 0 !important;
    }

    .main-navbar a.nav-link-lg:hover {
      background: rgba(255, 255, 255, 0.8) !important;
      color: var(--palette-primary, #e53935) !important;
      border-color: rgba(229, 57, 53, 0.2) !important;
      transform: scale(1.05);
    }

    .main-navbar a.nav-link-lg i {
      font-size: 18px !important;
    }

    /* User Profile & Notification Icons Wrapper */
    .main-navbar .navbar-right {
      display: flex !important;
      flex-direction: row !important;
      align-items: center !important;
      gap: 16px !important;
      margin-right: 12px !important;
      padding-left: 0 !important;
      list-style: none !important;
      margin-bottom: 0 !important;
    }

    .main-navbar .navbar-right>li {
      position: relative !important;
      float: none !important;
      display: block !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    .main-navbar .navbar-right>li>a {
      position: relative !important;
      float: none !important;
    }

    /* Notification Bell Toggle */
    .main-navbar .notification-toggle {
      width: 44px !important;
      height: 44px !important;
      border-radius: 50% !important;
      background: rgba(255, 255, 255, 0.5) !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      color: #475569 !important;
      position: relative !important;
      padding: 0 !important;
      transition: var(--transition-smooth) !important;
    }

    .main-navbar .notification-toggle:hover {
      background: rgba(255, 255, 255, 0.8) !important;
      color: var(--palette-primary, #e53935) !important;
      border-color: rgba(229, 57, 53, 0.2) !important;
      transform: translateY(-2px) !important;
    }

    .main-navbar .notification-toggle i {
      font-size: 20px !important;
      line-height: 1 !important;
      margin: 0 !important;
    }

    /* Premium notification badge with pulse animation */
    .main-navbar .notification-toggle .badge {
      position: absolute !important;
      top: -2px !important;
      right: -2px !important;
      width: 18px !important;
      height: 18px !important;
      padding: 0 !important;
      border-radius: 50% !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 0.65rem !important;
      font-weight: 800 !important;
      border: 2px solid #ffffff !important;
      background-color: var(--palette-primary, #e53935) !important;
      box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.4) !important;
      animation: pulseNotif 2s infinite !important;
    }

    @keyframes pulseNotif {
      0% {
        box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.4) !important;
      }

      70% {
        box-shadow: 0 0 0 6px rgba(229, 57, 53, 0) !important;
      }

      100% {
        box-shadow: 0 0 0 0 rgba(229, 57, 53, 0) !important;
      }
    }

    /* User Profile Dropdown Toggle */
    .main-navbar a.nav-link-lg.nav-link-user {
      display: flex !important;
      align-items: center !important;
      gap: 10px !important;
      padding: 6px 14px !important;
      background: rgba(255, 255, 255, 0.5) !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      border-radius: 30px !important;
      color: #475569 !important;
      font-weight: 600 !important;
      transition: var(--transition-smooth) !important;
      text-decoration: none !important;
      width: auto !important;
      height: auto !important;
    }

    .main-navbar a.nav-link-lg.nav-link-user::after {
      order: -1 !important;
      margin-top: 1px !important;
      margin-left: 0 !important;
      margin-right: 0px !important;
    }

    .main-navbar a.nav-link-lg.nav-link-user:hover {
      background: rgba(255, 255, 255, 0.8) !important;
      color: var(--palette-primary, #e53935) !important;
      border-color: rgba(229, 57, 53, 0.2) !important;
      transform: translateY(-2px) !important;
    }

    .main-navbar .nav-link-user img {
      width: 44px !important;
      height: 44px !important;
      object-fit: cover !important;
      border: 2px solid #ffffff !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
      margin: 0 !important;
    }

    .main-navbar .nav-link-user .d-lg-inline-block {
      font-size: 0.85rem !important;
      color: #334155 !important;
      line-height: 1 !important;
    }

    /* --- DROPDOWNS (Notifications & Profile) --- */
    .dropdown-menu {
      border: 1px solid #f1f5f9 !important;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06) !important;
      border-radius: 14px !important;
      padding: 8px !important;
      transform-origin: top right;
      animation: dropdownFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes dropdownFadeIn {
      from {
        opacity: 0;
        transform: translateY(10px) scale(0.98);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .dropdown-list {
      width: 320px !important;
      padding: 0 !important;
    }

    .dropdown-list .dropdown-header {
      background: #ffffff !important;
      color: #1e293b !important;
      font-size: 0.9rem !important;
      font-weight: 800 !important;
      padding: 16px 20px !important;
      border-bottom: 1px solid #f1f5f9 !important;
      border-radius: 14px 14px 0 0 !important;
    }

    .dropdown-list .dropdown-footer {
      padding: 12px 20px !important;
      border-top: 1px solid #f1f5f9 !important;
      border-radius: 0 0 14px 14px !important;
      background: #ffffff !important;
    }

    .dropdown-list .dropdown-footer a {
      color: var(--palette-primary, #e53935) !important;
      font-weight: 700 !important;
      font-size: 0.78rem !important;
      text-decoration: none;
      transition: var(--transition-smooth);
    }

    .dropdown-list .dropdown-footer a:hover {
      letter-spacing: 0.3px;
    }

    .dropdown-list-content {
      max-height: 300px;
      overflow-y: auto !important;
      scrollbar-width: thin !important;
      scrollbar-color: rgba(0, 0, 0, 0.15) transparent !important;
    }

    .dropdown-list-content::-webkit-scrollbar {
      display: block !important;
      width: 4px !important;
      height: 4px !important;
    }

    .dropdown-list-content::-webkit-scrollbar-track {
      background: transparent !important;
    }

    .dropdown-list-content::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1) !important;
      border-radius: 4px !important;
    }

    .dropdown-list-content:hover::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.25) !important;
    }

    /* Individual Notification Items */
    .dropdown-list .dropdown-item {
      padding: 14px 20px !important;
      border-bottom: 1px solid #f8fafc !important;
      display: flex !important;
      align-items: flex-start !important;
      gap: 12px !important;
      white-space: normal !important;
      transition: var(--transition-smooth) !important;
      border-radius: 0 !important;
      margin: 0 !important;
    }

    .dropdown-list .dropdown-item:hover {
      background-color: #f8fafc !important;
    }

    .dropdown-list .dropdown-item .dropdown-item-icon {
      width: 38px !important;
      height: 38px !important;
      border-radius: 50% !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      flex-shrink: 0;
    }

    .dropdown-list .dropdown-item .dropdown-item-icon i {
      font-size: 14px !important;
    }

    .dropdown-list .dropdown-item .dropdown-item-desc {
      font-size: 0.8rem !important;
      line-height: 1.4 !important;
      color: #475569 !important;
      flex: 1 !important;
      min-width: 0 !important;
    }

    .dropdown-list .dropdown-item .dropdown-item-desc b {
      color: #1e293b !important;
      display: block;
      margin-bottom: 2px;
      font-weight: 700;
    }

    .dropdown-list .dropdown-item .dropdown-item-desc p {
      color: #64748b;
      margin: 0 0 6px 0;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 100% !important;
    }

    /* User Profile Dropdown Menu */
    .dropdown-menu-end {
      margin-top: 8px !important;
    }

    .dropdown-menu .dropdown-title {
      font-size: 0.7rem !important;
      font-weight: 800 !important;
      color: #94a3b8 !important;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 8px 16px !important;
    }

    .dropdown-menu .dropdown-item {
      padding: 10px 16px !important;
      font-size: 0.85rem !important;
      font-weight: 600 !important;
      color: #475569 !important;
      border-radius: 8px !important;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: var(--transition-smooth);
    }

    .dropdown-menu .dropdown-item:hover {
      background: #f8fafc !important;
      color: var(--palette-primary, #e53935) !important;
    }

    .dropdown-menu .dropdown-item.text-danger:hover {
      background: #fff5f5 !important;
      color: #e53935 !important;
    }

    .dropdown-menu .dropdown-item i {
      font-size: 14px;
      width: 16px;
      display: flex;
      justify-content: center;
    }

    .dropdown-divider {
      margin: 6px 0 !important;
      border-color: #f1f5f9 !important;
    }

    /* Mobile Responsive Overrides */
    @media (max-width: 1024px) {
      .main-navbar {
        left: 16px !important;
        right: 16px !important;
        top: 16px !important;
        width: calc(100% - 32px) !important;
        border-radius: 14px !important;
        padding: 0 16px !important;
      }

      body.sidebar-show .main-sidebar {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12) !important;
      }
    }
  </style>

</head>

<body>
  <div id="notif-force-overlay">
    <div class="notif-box">
      <i class="fas fa-bell"></i>
      <h2>Aktifkan Notifikasi</h2>
      <p>Untuk mengakses dashboard admin, Anda wajib mengaktifkan notifikasi agar tetap mendapatkan update real-time
        mengenai pesanan dan status proyek.</p>

      <button id="btn-allow-notif" class="btn-notif">
        <i class="fas fa-check-circle me-2"></i> Klik untuk Izinkan
      </button>

      <div id="denied-msg" class="denied-instruction">
        <strong>PENTING:</strong> Anda sebelumnya memblokir notifikasi. <br>
        Silakan klik ikon gembok di pojok kiri atas (URL Bar) dan ubah izin Notifikasi menjadi <strong>"Allow"</strong>,
        lalu refresh halaman ini.
      </div>
    </div>
  </div>

  <div id="app">
    <div class="main-wrapper">

      <!-- Navbar (Bagian Atas) -->
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar pe-0">
        <form class="form-inline me-auto">
          <ul class="navbar-nav me-3">
            <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <!-- Notification Dropdown -->
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-bs-toggle="dropdown" class="nav-link notification-toggle nav-link-lg" id="notif-bell">
              <i class="far fa-bell"></i>
              <span class="badge bg-danger" id="notif-badge" style="display: none;">0</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-end">
              <div class="dropdown-header">Notifikasi
              </div>
              <div class="dropdown-list-content dropdown-list-icons" id="notif-list">
                <!-- Data akan dimuat via AJAX -->
                <div class="p-3 text-center text-muted">Memuat notifikasi...</div>
              </div>
              <div class="dropdown-footer text-center">
                <a href="<?= base_url('admin/notification') ?>">Lihat Semua <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>

          <li class="dropdown">
            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <?php
              $sessionPhoto = session()->get('photo') ?? null;
              $avatarUrl = $sessionPhoto
                ? (strpos($sessionPhoto, 'http') === 0 ? $sessionPhoto : base_url('uploads/profile/' . $sessionPhoto))
                : base_url('assets/img/avatar/avatar-5.png');
              ?>

              <div class="d-sm-none d-lg-inline-block"><?= esc(session()->get('full_name') ?? 'Admin') ?></div>
              <img alt="image" src="<?= $avatarUrl ?>" class="rounded-circle me-1">
            </a>
            <div class="dropdown-menu dropdown-menu-end">
              <div class="dropdown-title">
                <span
                  class="badge bg-primary text-white"><?= esc(ucwords(str_replace('_', ' ', session()->get('role') ?? 'admin'))) ?></span>
              </div>
              <div class="dropdown-divider"></div>
              <a href="<?= site_url('admin/logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Sidebar (Menu Samping) -->
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= site_url('admin/dashboard') ?>" class="d-flex align-items-center justify-content-start">
              <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo"
                style="width: 38px; height: 38px; object-fit: contain;">
              <span class="fs-4">PASANG<span class="text-danger">IN</span></span>
            </a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= site_url('admin/dashboard') ?>" class="d-flex align-items-center justify-content-center"
              style="height: 60px;">
              <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo"
                style="width: 30px; height: 30px; object-fit: contain;">
            </a>
          </div>

          <?php $uri = service('uri'); ?>
          <?php $seg2 = ($uri->getTotalSegments() >= 2) ? $uri->getSegment(2) : ''; ?>
          <?php
          $isAccounting = (strtolower(session()->get('role') ?? '') === 'accounting' || can('dashboard_accounting'));
          ?>

          <ul class="sidebar-menu">

            <!-- ============ DASHBOARD ============ -->
            <?php if (can('dashboard') || $isAccounting): ?>
              <li class="<?= ($seg2 == 'dashboard' || $seg2 == '') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/dashboard') ?>"><i class="fas fa-fire"></i>
                  <span>Dashboard</span></a>
              </li>
            <?php endif; ?>

            <!-- ============ PROYEK ============ -->
            <?php if (canAny(['design', 'construction', 'renovation']) || $isAccounting || in_array(strtolower(session()->get('role') ?? ''), ['drafter', 'arsitek'])): ?>
              <?php if (strtolower(session()->get('role') ?? '') === 'kepala divisi desain'): ?>
                <li
                  class="<?= ($seg2 == 'design' && $uri->getTotalSegments() >= 3 && $uri->getSegment(3) === 'managerial') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/design/managerial') ?>"><i class="fas fa-tasks"></i>
                    <span>Managerial Tugas</span></a>
                </li>
              <?php endif; ?>

              <?php if (in_array(strtolower(session()->get('role') ?? ''), ['drafter', 'arsitek'])): ?>
                <li
                  class="<?= ($seg2 == 'design' && $uri->getTotalSegments() >= 3 && $uri->getSegment(3) === 'tugas') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/design/tugas') ?>"><i class="fas fa-tasks"></i>
                    <span>Tugas</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('design') || $isAccounting): ?>
                <li
                  class="<?= ($seg2 == 'design' && ($uri->getTotalSegments() < 3 || ($uri->getSegment(3) !== 'managerial' && $uri->getSegment(3) !== 'tugas'))) ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/design') ?>"><i class="fas fa-paint-brush"></i>
                    <span>Desain</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('construction') || $isAccounting): ?>
                <li class="<?= ($seg2 == 'construction') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/construction') ?>"><i class="fas fa-building"></i>
                    <span>Konstruksi</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('renovation') || $isAccounting): ?>
                <li class="<?= ($seg2 == 'renovation') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/renovation') ?>"><i class="fas fa-paint-roller"></i>
                    <span>Renovasi</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ MANAJEMEN SINGLE ITEMS ============ -->
            <?php if (canAny(['chat', 'orders', 'users']) || $isAccounting): ?>
              <?php if (can('chat')): ?>
                <?php
                $chatSeg3 = ($uri->getTotalSegments() >= 3) ? $uri->getSegment(3) : '';
                $isChatActive = ($seg2 == 'chat');
                ?>
                <li class="dropdown <?= $isChatActive ? 'active' : '' ?>">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-comments"></i> <span>Pesan</span></a>
                  <ul class="dropdown-menu">
                    <li class="<?= ($isChatActive && $chatSeg3 == 'cs') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/chat/cs') ?>">
                        <i class="fas fa-headset"></i> <span>Customer Service</span>
                      </a>
                    </li>
                    <li class="<?= ($isChatActive && $chatSeg3 == 'monitoring') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/chat/monitoring') ?>">
                        <i class="fas fa-eye"></i> <span>Monitoring</span>
                      </a>
                    </li>
                    <li class="<?= ($isChatActive && $chatSeg3 == 'project') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/chat/project') ?>">
                        <i class="fas fa-hard-hat"></i> <span>Proyek</span>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php endif; ?>

              <?php if (canAny(['orders', 'users'])): ?>
                <li class="dropdown <?= ($seg2 == 'orders' || $seg2 == 'users') ? 'active' : '' ?>">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-bag"></i> <span>Client</span></a>
                  <ul class="dropdown-menu">
                    <?php if (can('orders')): ?>
                      <li class="<?= ($seg2 == 'orders') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= site_url('admin/orders') ?>"><i class="fas fa-shopping-cart"></i>
                          <span>Pesanan</span></a>
                      </li>
                    <?php endif; ?>

                    <?php if (can('users')): ?>
                      <li class="<?= ($seg2 == 'users') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>"><i class="fas fa-users"></i>
                          <span>Users</span></a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ DROPDOWNS AT THE BOTTOM ============ -->
            <?php if (canAny(['suppliers', 'products', 'banner_supplier', 'promo'])): ?>
              <li
                class="dropdown <?= ($seg2 == 'suppliers' || $seg2 == 'products' || $seg2 == 'banner-supplier' || $seg2 == 'promo') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-store"></i> <span>Supplier</span></a>
                <ul class="dropdown-menu">
                  <?php if (can('suppliers')): ?>
                    <li class="<?= ($seg2 == 'suppliers') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/suppliers') ?>"><i class="fas fa-store"></i>
                        <span>Suppliers</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('products')): ?>
                    <li class="<?= ($seg2 == 'products') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/products') ?>"><i class="fas fa-box"></i>
                        <span>Produk</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('banner_supplier')): ?>
                    <li class="<?= ($seg2 == 'banner-supplier') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/banner-supplier') ?>"><i class="fas fa-store"></i>
                        <span>Banner Supplier</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('promo')): ?>
                    <li class="<?= ($seg2 == 'promo') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/promo') ?>"><i class="fas fa-percentage"></i>
                        <span>Promosi</span></a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (canAny(['tukang', 'wallet']) || $isAccounting): ?>
              <li
                class="dropdown <?= ($seg2 == 'wallet' || $seg2 == 'tukang' || $seg2 == 'tukang-skill') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hard-hat"></i> <span>Tukang</span></a>
                <ul class="dropdown-menu">
                  <?php if (can('tukang')): ?>
                    <li class="<?= ($seg2 == 'tukang') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/tukang/index') ?>"><i class="fas fa-hard-hat"></i>
                        <span>Tukang</span></a>
                    </li>
                    <li class="<?= ($seg2 == 'tukang-skill') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/tukang-skill') ?>"><i class="fas fa-tools"></i>
                        <span>Tukang Skill</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('wallet') || $isAccounting): ?>
                    <li class="<?= ($seg2 == 'wallet') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/wallet') ?>"><i class="fas fa-wallet"></i> <span>Wallet
                          (Saldo)</span></a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (canAny(['admin', 'roles', 'activity_log_view', 'admin_balance_view']) || $isAccounting): ?>
              <li
                class="dropdown <?= in_array($seg2, ['admin', 'roles', 'activity-logs', 'admin-balance']) ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-shield"></i> <span>Admin</span></a>
                <ul class="dropdown-menu">
                  <?php if (can('admin')): ?>
                    <li class="<?= ($seg2 == 'admin') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/admin') ?>"><i class="fas fa-user-tie"></i> <span>Kelola
                          Admin</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('roles')): ?>
                    <li class="<?= ($seg2 == 'roles') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/roles') ?>"><i class="fas fa-user-shield"></i>
                        <span>Kelola Role</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('admin_balance_view') || $isAccounting): ?>
                    <li class="<?= ($seg2 == 'admin-balance') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/admin-balance') ?>"><i class="fas fa-university"></i>
                        <span>Saldo Admin</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('activity_log_view')): ?>
                    <li class="<?= ($seg2 == 'activity-logs') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/activity-logs') ?>"><i class="fas fa-history"></i>
                        <span>Log Aktivitas</span></a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (canAny(['banner', 'tips', 'notification', 'price-estimate', 'syarat_ketentuan', 'about_application'])): ?>
              <li
                class="dropdown <?= in_array($seg2, ['banner', 'tips', 'notification', 'price-estimate', 'syarat_ketentuan', 'about_application']) ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Kelola Konten</span></a>
                <ul class="dropdown-menu">
                  <?php if (can('banner')): ?>
                    <li class="<?= ($seg2 == 'banner') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/banner') ?>"><i class="fas fa-image"></i>
                        <span>Banner</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('tips')): ?>
                    <li class="<?= ($seg2 == 'tips') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/tips') ?>"><i class="fas fa-lightbulb"></i>
                        <span>Tips</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('notification')): ?>
                    <li class="<?= ($seg2 == 'notification') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/notification') ?>"><i class="fas fa-bell"></i>
                        <span>Notifikasi</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('price-estimate')): ?>
                    <li class="<?= ($seg2 == 'price-estimate') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/price-estimate') ?>"><i class="fas fa-calculator"></i>
                        <span>Estimasi Harga</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('syarat_ketentuan')): ?>
                    <li class="<?= ($seg2 == 'syarat_ketentuan') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/syarat_ketentuan') ?>"><i
                          class="fas fa-file-contract"></i> <span>Syarat & Ketentuan</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('about_application')): ?>
                    <li class="<?= ($seg2 == 'about_application') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/about_application') ?>"><i
                          class="fas fa-info-circle"></i> <span>Tentang Aplikasi</span></a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (canAny(['settings_view', 'vouchers', 'ahsp', 'satuan', 'product_categories_view']) || $isAccounting): ?>
              <li class="dropdown <?= in_array($seg2, ['settings', 'vouchers', 'satuan', 'ahsp', 'product-categories']) ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>Pengaturan</span></a>
                <ul class="dropdown-menu">
                  <?php if (can('settings_view')): ?>
                    <li class="<?= ($seg2 == 'settings') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/settings') ?>"><i class="fas fa-cog"></i>
                        <span>Pengaturan Aplikasi</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('product_categories_view')): ?>
                    <li class="<?= ($seg2 == 'product-categories') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/product-categories') ?>"><i class="fas fa-tags"></i>
                        <span>Kategori Produk</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('vouchers') || $isAccounting): ?>
                    <li class="<?= ($seg2 == 'vouchers') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/vouchers') ?>"><i class="fas fa-ticket-alt"></i>
                        <span>Voucher</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('ahsp')): ?>
                    <li class="<?= ($seg2 == 'ahsp') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/ahsp') ?>"><i class="fas fa-clipboard-list"></i>
                        <span>AHSP</span></a>
                    </li>
                  <?php endif; ?>
                  <?php if (can('satuan')): ?>
                    <li class="<?= ($seg2 == 'satuan') ? 'active' : '' ?>">
                      <a class="nav-link" href="<?= site_url('admin/satuan') ?>"><i class="fas fa-balance-scale"></i>
                        <span>Satuan</span></a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <?= $this->renderSection('content') ?>
      </div>

      <!-- Footer -->
      <footer class="main-footer d-flex justify-content-center">
        <div class="footer-left ">
          Copyright &copy; <?= date('Y') ?>
        </div>
      </footer>
    </div>
  </div>

  <!-- Global Modals -->
  <?= $this->include('components/_global_delete_modal') ?>
  <?= $this->include('components/_global_status_modal') ?>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- GLightbox JS -->
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof GLightbox !== 'undefined') {
        window.globalLightbox = GLightbox({
          selector: '.glightbox',
          zoomable: true,
          draggable: true,
          openEffect: 'zoom',
          closeEffect: 'zoom',
          slideEffect: 'slide',
          closeOnOutsideClick: true,
          keyboardNavigation: true,
          touchNavigation: true,
          descPosition: 'bottom'
        });
      }
    });
  </script>
  <!-- JS Libraries -->
  <?= $this->renderSection('script') ?>

  <!-- Template JS File -->
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>

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
    const overlay = document.getElementById('notif-force-overlay');
    const deniedMsg = document.getElementById('denied-msg');
    const btnAllow = document.getElementById('btn-allow-notif');

    function checkNotifPermission() {
      if (!('Notification' in window)) {
        console.warn('Browser ini tidak mendukung notifikasi.');
        overlay.style.display = 'none';
        return;
      }
      if (Notification.permission === 'granted') {
        overlay.style.display = 'none';

        // Selalu daftarkan Service Worker jika sudah granted agar bisa terima notif saat browser closed
        if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('<?= base_url('firebase-messaging-sw.js') ?>')
            .then(function (registration) {
              console.log('FCM Service Worker registered');
              // Ambil token setelah SW siap, sertakan registration
              getFCMToken(registration);
            });
        }
      } else if (Notification.permission === 'denied') {
        overlay.style.display = 'flex';
        deniedMsg.style.display = 'block';
        btnAllow.style.display = 'none';
      } else {
        overlay.style.display = 'flex';
        deniedMsg.style.display = 'none';
        btnAllow.style.display = 'inline-block';
      }
    }

    function getFCMToken(registration) {
      messaging.getToken({
        vapidKey: 'BOZaLfwpGedtEE1EmKYvscUvpciGC0eSw09Z6ro9CAwkiFT14_N2hlfkg2eKkx6CA6IA3FJ44nuFgmi3UKRF31g',
        serviceWorkerRegistration: registration
      }).then((currentToken) => {
        if (currentToken) {
          console.log('FCM Token:', currentToken);

          // Gunakan try-catch agar aman dari Tracking Prevention yang memblokir akses ke storage
          let shouldSave = true;
          const currentAdminId = '<?= session()->get('user_id') ?>';
          try {
            if (typeof window.localStorage !== 'undefined') {
              const savedToken = localStorage.getItem('fcm_token_saved');
              const savedAdminId = localStorage.getItem('fcm_token_admin_id');
              if (savedToken === currentToken && savedAdminId === currentAdminId) {
                shouldSave = false;
                console.log('FCM Token already saved for current admin session.');
              }
            }
          } catch (e) {
            console.warn('Storage access blocked by Tracking Prevention, falling back to direct save:', e);
          }

          if (shouldSave) {
            $.post('<?= base_url('admin/notification/saveToken') ?>', {
              token: currentToken
            }, function (res) {
              console.log('Token saved to backend:', res);
              if (res && res.status) {
                try {
                  if (typeof window.localStorage !== 'undefined') {
                    localStorage.setItem('fcm_token_saved', currentToken);
                    localStorage.setItem('fcm_token_admin_id', currentAdminId);
                  }
                } catch (e) {
                  // Gagal menyimpan cache (misal storage diblokir), abaikan saja
                }
              }
            });
          }
        }
      }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
      });
    }

    btnAllow.addEventListener('click', function () {
      Notification.requestPermission().then((permission) => {
        checkNotifPermission();
      });
    });

    // Jalankan pengecekan awal
    checkNotifPermission();

    // --- LOGIKA NAVBAR NOTIFIKASI ---
    function loadNavbarNotifications() {
      $.get('<?= base_url('admin/notification/getLatest') ?>', function (data) {
        let html = '';
        if (data.length === 0) {
          html = '<div class="p-3 text-center text-muted">Tidak ada notifikasi baru</div>';
        } else {
          data.forEach(function (n) {
            let icon = 'fa-bell';
            let bg = 'bg-primary';

            if (n.target_type && n.target_type.includes('client')) { icon = 'fa-user'; bg = 'bg-info'; }
            else if (n.target_type && n.target_type.includes('tukang')) { icon = 'fa-tools'; bg = 'bg-warning'; }
            else if (n.target_type && n.target_type.includes('supplier')) { icon = 'fa-store'; bg = 'bg-success'; }

            // Gunakan moment ID untuk waktu yang lebih manusiawi
            moment.locale('id');
            let relativeTime = moment(n.created_at).fromNow();
            let fullDateTime = moment(n.created_at).format('dddd, DD MMM YYYY - HH:mm');

            html += `
              <a href="<?= base_url('admin/notification') ?>" class="dropdown-item">
                <div class="dropdown-item-icon ${bg} text-white">
                  <i class="fas ${icon}"></i>
                </div>
                <div class="dropdown-item-desc">
                  <b>${n.title}</b>
                  <p class="mb-0 text-truncate" style="max-width: 100%;">${n.message}</p>
                  <div class="time text-primary fw-bold" style="font-size: 0.65rem; margin-top: -3px;">${fullDateTime}</div>
                </div>
              </a>`;
          });
        }
        $('#notif-list').html(html);
      });
    }

    // Load saat pertama kali buka
    loadNavbarNotifications();

    messaging.onMessage((payload) => {
      console.log('Message received: ', payload);

      // Dispatch custom event to window so active chat view can listen
      const chatEvent = new CustomEvent('fcm_chat_received', { detail: payload });
      window.dispatchEvent(chatEvent);

      // Update Navbar & Badge
      loadNavbarNotifications();
      $('#notif-badge').text('!').fadeIn();

      if (typeof iziToast !== 'undefined') {
        iziToast.info({
          title: payload.notification.title,
          message: payload.notification.body,
          position: 'topCenter',
          displayMode: 'replace',
          timeout: 5000
        });
      } else {
        alert(payload.notification.title + "\n" + payload.notification.body);
      }
    });
  </script>
</body>

</html>