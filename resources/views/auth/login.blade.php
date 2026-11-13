<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EventCraft (Laravel 11)</title>
  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Stylesheet EventCraft -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
      padding: 24px;
      font-family: 'Inter', sans-serif;
    }
    .login-container {
      width: 100%;
      max-width: 460px;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.1);
      animation: fadeIn 0.4s ease;
    }
    .login-header {
      background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
      padding: 32px 28px;
      text-align: center;
      color: #ffffff;
    }
    .login-header .logo-badge {
      width: 52px;
      height: 52px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 12px;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .login-header h1 {
      font-size: 1.45rem;
      font-weight: 700;
      margin: 0 0 6px;
      letter-spacing: -0.02em;
    }
    .login-header p {
      font-size: 0.85rem;
      opacity: 0.9;
      margin: 0;
    }
    .login-body {
      padding: 28px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-size: 0.825rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }
    .input-wrapper {
      position: relative;
    }
    .input-wrapper svg {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      width: 18px;
      height: 18px;
    }
    .input-control {
      width: 100%;
      padding: 10px 14px 10px 38px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      font-size: 0.9rem;
      color: #1e293b;
      background: #f8fafc;
      transition: all 0.2s ease;
      box-sizing: border-box;
    }
    .input-control:focus {
      outline: none;
      border-color: #6366f1;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .btn-login {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
      color: #ffffff;
      border: none;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25);
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 12px -2px rgba(79, 70, 229, 0.35);
    }
    .demo-accounts-box {
      margin-top: 24px;
      padding: 16px;
      background: #f1f5f9;
      border-radius: 10px;
      border: 1px dashed #cbd5e1;
    }
    .demo-accounts-title {
      font-size: 0.775rem;
      font-weight: 700;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .demo-btn-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
    }
    .btn-demo {
      padding: 8px 10px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      border-radius: 6px;
      font-size: 0.775rem;
      font-weight: 600;
      cursor: pointer;
      text-align: left;
      transition: all 0.2s ease;
    }
    .btn-demo:hover {
      border-color: #6366f1;
      background: #eef2ff;
      color: #4338ca;
    }
    .btn-demo small {
      display: block;
      font-size: 0.7rem;
      font-weight: 400;
      color: #64748b;
    }
    .alert-error {
      background-color: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
      padding: 12px 14px;
      border-radius: 8px;
      font-size: 0.825rem;
      margin-bottom: 18px;
    }
    .alert-success {
      background-color: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
      padding: 12px 14px;
      border-radius: 8px;
      font-size: 0.825rem;
      margin-bottom: 18px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="login-container">
    <!-- Header -->
    <div class="login-header">
      <div class="logo-badge">E</div>
      <h1>EventCraft System</h1>
      <p>Autentikasi Multi-Role & Modul Tiket (Laravel 11)</p>
    </div>

    <!-- Body -->
    <div class="login-body">

      <!-- Alert Pesan Sukses / Error -->
      @if (session('success'))
        <div class="alert-success">
          <strong>Berhasil:</strong> {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert-error">
          <strong>Peringatan:</strong> {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert-error">
          <strong>Gagal Masuk:</strong>
          <ul style="margin: 4px 0 0 16px; padding: 0;">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Form Login -->
      <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label" for="email">Alamat Email</label>
          <div class="input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <input type="email" id="email" name="email" class="input-control" value="{{ old('email') }}" placeholder="nama@eventcraft.com" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
            <label class="form-label" for="password" style="margin-bottom:0;">Password</label>
          </div>
          <div class="input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <input type="password" id="password" name="password" class="input-control" placeholder="••••••••" required>
          </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
          <label style="display:flex; align-items:center; gap:8px; font-size:0.825rem; color:#475569; cursor:pointer;">
            <input type="checkbox" name="remember" style="accent-color: #4f46e5;">
            <span>Ingat Saya</span>
          </label>
          <span style="font-size:0.775rem; color:#64748b;">PAW Pekan 7</span>
        </div>

        <button type="submit" class="btn-login">
          <span>Masuk ke Dashboard</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </form>

      <!-- Quick Demo Login Presets (Untuk Mempermudah Demo & Pengujian Dosen) -->
      <div class="demo-accounts-box">
        <div class="demo-accounts-title">
          <span>Akun Demo Praktikum:</span>
          <span style="font-size: 0.675rem; font-weight: 500; color: #6366f1;">Klik untuk auto-fill</span>
        </div>
        <div class="demo-btn-group">
          <button type="button" class="btn-demo" onclick="fillAccount('admin@eventcraft.com', 'admin123')">
            <strong style="color: #b91c1c;">1. Role Admin</strong>
            <small>Full Akses (+ Hapus)</small>
          </button>
          <button type="button" class="btn-demo" onclick="fillAccount('staff@eventcraft.com', 'staff123')">
            <strong style="color: #4338ca;">2. Role Staff</strong>
            <small>Lihat, Tambah, Ubah</small>
          </button>
        </div>
      </div>

    </div>
  </div>

  <script>
    function fillAccount(email, password) {
      document.getElementById('email').value = email;
      document.getElementById('password').value = password;
    }
  </script>
</body>
</html>
