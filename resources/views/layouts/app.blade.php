  <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Kategori Tiket & Benefit') - EventCraft (Laravel)</title>
  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Stylesheet EventCraft -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .alert-banner {
      padding: 14px 20px;
      border-radius: var(--radius-md);
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      font-size: 0.875rem;
      font-weight: 500;
      animation: slideDown 0.3s ease;
    }
    .alert-banner.alert-success {
      background-color: var(--business-bg);
      border: 1px solid var(--business-border);
      color: var(--business);
    }
    .alert-banner.alert-error {
      background-color: var(--danger-bg);
      border: 1px solid var(--danger-border);
      color: var(--danger);
    }
    .btn-remove-benefit {
      background: none;
      border: 1px solid var(--slate-200);
      color: var(--slate-400);
      cursor: pointer;
      padding: 6px 10px;
      border-radius: var(--radius-sm);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }
    .btn-remove-benefit:hover {
      color: var(--danger);
      background-color: var(--danger-bg);
      border-color: var(--danger-border);
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
  @stack('styles')
</head>
<body>

<div class="app-container">
  <!-- ========================================================================
       1. SIDEBAR NAVIGASI
       ======================================================================== -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon">E</div>
      <div class="brand-text">
        <h2>EventCraft</h2>
        <span>Sistem Event & Workshop (Laravel)</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-title">Modul Utama Kelompok</div>
      
      <!-- Modul Master Event (Individu A - NONAKTIF / POPUP MODAL PURE CSS) -->
      <a href="#modal-peer-A" class="nav-item inactive-badge" title="Klik untuk melihat spesifikasi arsitektur & relasi Modul A">
        <span style="display:flex; align-items:center; gap:12px;">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <span style="display: flex; flex-direction: column;">
            <strong style="font-size: 0.85rem; color: var(--slate-700);">Master Event & Silabus</strong>
            <small style="font-size: 0.7rem; color: var(--slate-400);">Individu A • Master Data</small>
          </span>
        </span>
        <span class="tag-module">Modul A</span>
      </a>

      <!-- Modul Pembicara / Speaker (Individu B - NONAKTIF / POPUP MODAL PURE CSS) -->
      <a href="#modal-peer-B" class="nav-item inactive-badge" title="Klik untuk melihat spesifikasi arsitektur & relasi Modul B">
        <span style="display:flex; align-items:center; gap:12px;">
          <svg viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span style="display: flex; flex-direction: column;">
            <strong style="font-size: 0.85rem; color: var(--slate-700);">Pendaftaran Speaker</strong>
            <small style="font-size: 0.7rem; color: var(--slate-400);">Individu B • Narasumber</small>
          </span>
        </span>
        <span class="tag-module">Modul B</span>
      </a>

      <!-- Modul Kategori Tiket (Individu C - AKTIF DI KERJAKAN) -->
      <a href="{{ route('tickets.index') }}" class="nav-item {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
          <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
          <path d="M13 5v2"></path>
          <path d="M13 17v2"></path>
          <path d="M13 11v2"></path>
        </svg>
        <span style="display: flex; flex-direction: column;">
          <strong style="font-size: 0.85rem; color: var(--primary);">Kategori Tiket & Benefit</strong>
          <small style="font-size: 0.7rem; color: var(--primary); opacity: 0.85;">Individu C • CRUD Laravel</small>
        </span>
      </a>

      <!-- Modul Direktori Peserta (Individu D - NONAKTIF / POPUP MODAL PURE CSS) -->
      <a href="#modal-peer-D" class="nav-item inactive-badge" title="Klik untuk melihat spesifikasi arsitektur & relasi Modul D">
        <span style="display:flex; align-items:center; gap:12px;">
          <svg viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span style="display: flex; flex-direction: column;">
            <strong style="font-size: 0.85rem; color: var(--slate-700);">Direktori Peserta</strong>
            <small style="font-size: 0.7rem; color: var(--slate-400);">Individu D • Registrasi & QR</small>
          </span>
        </span>
        <span class="tag-module">Modul D</span>
      </a>

      <div class="nav-section-title">Laravel Framework & MVC</div>
      <a href="https://laravel.com/docs" target="_blank" class="nav-item">
        <svg viewBox="0 0 24 24">
          <polyline points="16 18 22 12 16 6"></polyline>
          <polyline points="8 6 2 12 8 18"></polyline>
        </svg>
        <span>Dokumentasi Laravel 11</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-snippet">
        @auth
          <div class="user-avatar" style="background: {{ Auth::user()->role === 'admin' ? 'linear-gradient(135deg, #ef4444, #b91c1c)' : 'linear-gradient(135deg, #4f46e5, #9333ea)' }};">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
          </div>
          <div class="user-info">
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role">{{ Auth::user()->role === 'admin' ? 'Admin • Full Access' : 'Staff Tiket • Read/Write' }}</div>
          </div>
        @else
          <div class="user-avatar" style="background: var(--slate-300);">?</div>
          <div class="user-info">
            <div class="user-name">Guest</div>
            <div class="user-role">Belum Login</div>
          </div>
        @endauth
      </div>
    </div>
  </aside>

  <!-- ========================================================================
       2. MAIN CONTENT WRAPPER
       ======================================================================== -->
  <main class="main-wrapper">
    <!-- Top Header / Navbar (Menampilkan Nama & Role Pengguna Aktif) -->
    <header class="top-header">
      <div style="font-size: 0.85rem; color: var(--slate-500); display: flex; align-items: center; gap: 6px;">
        <span style="color: var(--primary); font-weight: 600;">EventCraft Laravel</span>
        <span>/</span>
        <span>Modul Kategori Tiket & Benefit (Individu C)</span>
      </div>

      <div class="header-actions" style="display: flex; align-items: center; gap: 12px;">
        @auth
          <!-- User Profile & Role Info di Navbar -->
          <div style="display: flex; align-items: center; gap: 8px; padding-right: 8px; border-right: 1px solid var(--slate-200);">
            <div style="text-align: right;">
              <div style="font-size: 0.825rem; font-weight: 600; color: var(--slate-800);">
                {{ Auth::user()->name }}
              </div>
              <div style="font-size: 0.725rem; color: var(--slate-400);">
                {{ Auth::user()->email }}
              </div>
            </div>

            <!-- Role Badge: Admin / Staff -->
            @if(Auth::user()->role === 'admin')
              <span class="badge" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-weight: 700; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Admin
              </span>
            @else
              <span class="badge" style="background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-weight: 700; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Staff Tiket
              </span>
            @endif
          </div>
        @endauth

        <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <span>+ Tambah Tiket Baru</span>
        </a>

        @auth
          <!-- Tombol Logout -->
          <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; color: #dc2626; border-color: #fca5a5;" title="Keluar / Logout dari Sistem">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              <span>Logout</span>
            </button>
          </form>
        @endauth
      </div>
    </header>

    <!-- Content Body -->
    <div class="content-body">
      
      <!-- Flash Alert Notification -->
      @if (session('success'))
        <div class="alert-banner alert-success">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span><strong>Berhasil:</strong> {{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" style="background:none; border:none; color:var(--business); font-weight:700; cursor:pointer; font-size:1.1rem;">&times;</button>
        </div>
      @endif

      @if (session('error') || (isset($errors) && $errors->any()))
        <div class="alert-banner alert-error">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
              <strong>Terjadi Kesalahan:</strong>
              @if (session('error'))
                <div>{{ session('error') }}</div>
              @endif
              @if (isset($errors) && $errors->any())
                <ul style="margin:4px 0 0 18px; font-size:0.825rem;">
                  @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
          <button onclick="this.parentElement.remove()" style="background:none; border:none; color:var(--danger); font-weight:700; cursor:pointer; font-size:1.1rem;">&times;</button>
        </div>
      @endif

      @yield('content')

      <!-- ====================================================================
           KARTU IDENTITAS MAHASISWA (STUDENT IDENTITY FOOTER)
           ==================================================================== -->
      <footer class="student-identity-footer">
        <div class="identity-card">
          <div class="identity-header">
            <span class="identity-badge">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
              Tugas Besar Pemrograman Aplikasi Web (Pekan 6 - Demo CRUD Laravel)
            </span>
            <span class="identity-semester">Semester Genap 2025/2026 • Telkom University</span>
          </div>

          <div class="identity-grid">
            <div class="identity-item">
              <span class="identity-label">Nama Mahasiswa</span>
              <span class="identity-value">Alwan Rifky Muhana</span>
            </div>

            <div class="identity-item">
              <span class="identity-label">Nomor Induk Mahasiswa (NIM)</span>
              <span class="identity-value highlight">102022500332</span>
            </div>

            <div class="identity-item">
              <span class="identity-label">Kelas Perkuliahan</span>
              <span class="identity-value">SI-49-07 (Sistem Informasi)</span>
            </div>

            <div class="identity-item">
              <span class="identity-label">Modul Individual yang Dikerjakan</span>
              <span class="identity-value modul-tag">Individu C: Kategori Tiket & Benefit</span>
            </div>

            <div class="identity-item identity-full">
              <span class="identity-label">Arsitektur MVC Laravel yang Digunakan</span>
              <p class="identity-desc">
                Proyek ini diimplementasikan menggunakan framework <strong>Laravel 11</strong> dengan pola arsitektur 
                <strong>MVC (Model-View-Controller)</strong>. Model <code>TicketCategory</code> berelasi <em>One-to-Many</em> 
                dengan <code>TicketBenefit</code>. Operasi CRUD ditangani oleh <code>TicketCategoryController</code> 
                melalui route resource <code>Route::resource('tickets', ...)</code> dan antarmuka responsif menggunakan template engine <strong>Blade</strong>.
              </p>
            </div>
          </div>
        </div>
      </footer>

    </div>
  </main>
</div>

<!-- ========================================================================
     MODAL POPUP PEER MODULES (Modul A, B, D)
     ======================================================================== -->
<div class="modal-overlay" id="modal-peer-A">
  <a href="#" class="modal-backdrop-close" aria-label="Tutup modal"></a>
  <div class="modal-container">
    <div class="modal-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span class="module-header-badge">Modul A</span>
        <h3 class="modal-title">Spesifikasi Modul Otonom</h3>
      </div>
      <a href="#" class="modal-close" title="Tutup">&times;</a>
    </div>
    <div class="modal-body">
      <div class="module-header-box">
        <div class="module-header-left">
          <h3>Penanggung Jawab: Individu A</h3>
          <span>Arsitektur Sistem Otonom & Interkoneksi Modul Master Event</span>
        </div>
      </div>
      <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6;">
        Modul otonom yang mengelola entitas master kegiatan, kurikulum materi silabus berbobot durasi sesi, dan kategorisasi bidang teknologi/bisnis.
      </p>
    </div>
    <div class="modal-footer"><a href="#" class="btn btn-secondary">Tutup Penjelasan</a></div>
  </div>
</div>

<div class="modal-overlay" id="modal-peer-B">
  <a href="#" class="modal-backdrop-close" aria-label="Tutup modal"></a>
  <div class="modal-container">
    <div class="modal-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span class="module-header-badge">Modul B</span>
        <h3 class="modal-title">Spesifikasi Modul Otonom</h3>
      </div>
      <a href="#" class="modal-close" title="Tutup">&times;</a>
    </div>
    <div class="modal-body">
      <div class="module-header-box">
        <div class="module-header-left">
          <h3>Penanggung Jawab: Individu B</h3>
          <span>Arsitektur Sistem Otonom & Interkoneksi Modul Speaker</span>
        </div>
      </div>
      <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6;">
        Modul otonom yang bertanggung jawab mengorganisir kurasi profil narasumber, biodata profesional, keahlian teknis, afiliasi institusi, serta penugasan pembicara ke dalam sesi silabus Master Event.
      </p>
    </div>
    <div class="modal-footer"><a href="#" class="btn btn-secondary">Tutup Penjelasan</a></div>
  </div>
</div>

<div class="modal-overlay" id="modal-peer-D">
  <a href="#" class="modal-backdrop-close" aria-label="Tutup modal"></a>
  <div class="modal-container">
    <div class="modal-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span class="module-header-badge">Modul D</span>
        <h3 class="modal-title">Spesifikasi Modul Otonom</h3>
      </div>
      <a href="#" class="modal-close" title="Tutup">&times;</a>
    </div>
    <div class="modal-body">
      <div class="module-header-box">
        <div class="module-header-left">
          <h3>Penanggung Jawab: Individu D</h3>
          <span>Arsitektur Sistem Otonom & Interkoneksi Direktori Peserta</span>
        </div>
      </div>
      <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6;">
        Modul otonom yang mengelola biodata profil peserta terdaftar, penerbitan e-tiket berbasis QR Code, verifikasi kehadiran per sesi silabus, dan pencetakan sertifikat kelulusan workshop.
      </p>
    </div>
    <div class="modal-footer"><a href="#" class="btn btn-secondary">Tutup Penjelasan</a></div>
  </div>
</div>

@stack('modals')
@stack('scripts')
</body>
</html>
