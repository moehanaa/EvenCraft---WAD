@extends('layouts.app')

@section('title', 'Daftar Kategori Tiket & Benefit')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div>
    <h1 class="page-title">Klasifikasi Kategori Tiket & Fasilitas Benefit</h1>
    <p class="page-subtitle">Kelola tingkatan tiket (Early Bird, Regular, VIP), harga, alokasi kuota kursi, serta rincian fasilitas benefit peserta event menggunakan Laravel 11 & MySQL.</p>
  </div>
  <div>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm" style="margin-top: 6px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>+ Tambah Tiket Baru</span>
    </a>
  </div>
</div>

<!-- Stat Cards Overview (Dihitung Dinamis dari Database) -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon icon-purple">
      <svg viewBox="0 0 24 24">
        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
        <path d="M13 5v2"></path>
        <path d="M13 17v2"></path>
        <path d="M13 11v2"></path>
      </svg>
    </div>
    <div class="stat-data">
      <div class="stat-label">Total Kategori Tiket</div>
      <div class="stat-value">{{ $totalKategori }} Kategori</div>
      <div class="stat-subtext">Semua tier tiket terdaftar</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon icon-blue">
      <svg viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
        <circle cx="9" cy="7" r="4"></circle>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
      </svg>
    </div>
    <div class="stat-data">
      <div class="stat-label">Tiket Terjual & Okupansi</div>
      <div class="stat-value">{{ number_format($totalTerjual) }} / {{ number_format($totalKuota) }} ({{ $persenOkupansi }}%)</div>
      <div class="stat-subtext">Total tiket terisi dari kuota</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon icon-green">
      <svg viewBox="0 0 24 24">
        <line x1="12" y1="1" x2="12" y2="23"></line>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
      </svg>
    </div>
    <div class="stat-data">
      <div class="stat-label">Estimasi Total Pendapatan</div>
      <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
      <div class="stat-subtext">Akumulasi hasil penjualan</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon icon-amber">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
    </div>
    <div class="stat-data">
      <div class="stat-label">Rata-Rata Harga Tiket</div>
      <div class="stat-value">Rp {{ number_format($rataRataHarga, 0, ',', '.') }}</div>
      <div class="stat-subtext">Nilai rata-rata per kategori</div>
    </div>
  </div>
</div>

<!-- Filter Bar & Search Toolbar -->
<div class="filter-toolbar">
  <div class="category-tabs">
    <input type="radio" name="tier_filter" id="filter-all" class="filter-radio" checked>
    <label for="filter-all" class="cat-tab">Semua Kategori</label>

    <input type="radio" name="tier_filter" id="filter-early" class="filter-radio">
    <label for="filter-early" class="cat-tab">Early Bird</label>

    <input type="radio" name="tier_filter" id="filter-regular" class="filter-radio">
    <label for="filter-regular" class="cat-tab">Regular</label>

    <input type="radio" name="tier_filter" id="filter-vip" class="filter-radio">
    <label for="filter-vip" class="cat-tab">VIP</label>
  </div>

  <div class="table-search-box">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="8"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
    <input type="text" id="tableSearchInput" placeholder="Pencarian kategori tiket...">
  </div>
</div>

<!-- Tabel Master Kategori Tiket -->
<div class="table-card">
  <div class="table-responsive">
    <table class="custom-table" id="ticketsTable">
      <thead>
        <tr>
          <th style="width: 90px;">ID Tiket</th>
          <th>Kategori & Event Terkait</th>
          <th style="width: 130px;">Tipe Tiket</th>
          <th style="width: 140px;">Harga Tiket</th>
          <th style="width: 170px;">Kuota & Okupansi</th>
          <th style="width: 220px;">Fasilitas Benefit</th>
          <th style="width: 100px;">Status</th>
          <th style="width: 150px; text-align: center;">Aksi CRUD</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tickets as $ticket)
          @php
            $rowClass = match($ticket->tipe_tier) {
              'Early Bird' => 'row-tier-early',
              'Regular'    => 'row-tier-regular',
              default      => 'row-tier-vip',
            };
            $badgeClass = match($ticket->tipe_tier) {
              'Early Bird' => 'badge-tier-early',
              'Regular'    => 'badge-tier-regular',
              default      => 'badge-tier-vip',
            };
            $fillClass = ($ticket->persen_okupansi >= 90) ? 'fill-danger' : (($ticket->persen_okupansi >= 70) ? 'fill-warning' : '');
            $sisa = $ticket->sisa_kuota;
            $eventName = $events[$ticket->event_id] ?? 'Event Terdaftar';
            $benefitsCount = $ticket->benefits->count();
          @endphp
          <tr class="{{ $rowClass }}">
            <td style="font-family: monospace; font-size: 0.8rem; font-weight: 600; color: var(--slate-500);">
              {{ $ticket->code }}
            </td>
            <td>
              <div class="event-cell-info">
                <span class="event-cell-title">{{ $ticket->nama_kategori }}</span>
                <span class="event-cell-desc" style="color: var(--primary); font-weight: 500;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 3px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  </svg>
                  {{ $eventName }}
                </span>
                <small style="font-size: 0.725rem; color: var(--slate-400);">{{ $ticket->deskripsi ?? '-' }}</small>
              </div>
            </td>
            <td>
              <span class="badge {{ $badgeClass }}">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                </svg>
                {{ $ticket->tipe_tier }}
              </span>
            </td>
            <td>
              <strong style="color: var(--slate-900); font-family: monospace; font-size: 0.95rem;">
                {{ $ticket->formatted_price }}
              </strong>
              <div style="font-size: 0.725rem; color: var(--slate-400);">per tiket</div>
            </td>
            <td>
              <div class="quota-progress-wrap">
                <div class="quota-label-row">
                  <span>Terjual: <strong>{{ $ticket->terjual }}/{{ $ticket->kuota }}</strong></span>
                  <span><strong>{{ $ticket->persen_okupansi }}%</strong></span>
                </div>
                <div class="quota-bar-track">
                  <div class="quota-bar-fill {{ $fillClass }}" style="width: {{ $ticket->persen_okupansi }}%;"></div>
                </div>
                <span style="font-size: 0.7rem; color: var(--slate-500);">
                  Sisa Kuota: <strong style="color: {{ $sisa <= 5 ? '#dc2626' : 'inherit' }};">{{ $sisa }} kursi</strong>
                </span>
              </div>
            </td>
            <td>
              <div class="benefit-pill-list">
                @if ($benefitsCount > 0)
                  @foreach ($ticket->benefits->take(2) as $bf)
                    <span class="benefit-pill">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                      {{ $bf->benefit }}
                    </span>
                  @endforeach
                  @if ($benefitsCount > 2)
                    <a href="#modal-ticket-{{ $ticket->code }}" class="benefit-pill-more">+{{ $benefitsCount - 2 }} lainnya</a>
                  @endif
                @else
                  <span style="font-size:0.75rem; color:var(--slate-400); font-style:italic;">Tidak ada benefit</span>
                @endif
              </div>
            </td>
            <td>
              @if ($sisa === 0)
                <span class="badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">Habis</span>
              @elseif ($sisa <= 5)
                <span class="badge" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a;">Terbatas</span>
              @else
                <span class="badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;">Tersedia</span>
              @endif
            </td>
            <td>
              <div class="table-actions" style="justify-content: center;">
                <!-- READ: Lihat Modal Detail -->
                <a href="#modal-ticket-{{ $ticket->code }}" class="action-btn" title="Lihat Detail Fasilitas Benefit">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </a>

                <!-- UPDATE: Edit Tiket -->
                <a href="{{ route('tickets.edit', $ticket->id_tiket) }}" class="action-btn" title="Edit Kategori Tiket">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                </a>

                <!-- DELETE: Hapus Tiket (Hanya Ditampilkan untuk Role Admin) -->
                @if(auth()->user()->role === 'admin')
                  <form action="{{ route('tickets.destroy', $ticket->id_tiket) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori tiket \'{{ $ticket->nama_kategori }}\'? Data benefit terkait juga akan terhapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete" title="Hapus Kategori Tiket (Admin Only)" style="color:var(--danger); border-color:var(--slate-200);">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                      </svg>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="table-empty">
              <p style="font-size: 1rem; font-weight: 600; color: var(--slate-600); margin-bottom: 6px;">Belum ada kategori tiket tersimpan di database.</p>
              <p style="font-size: 0.85rem; color: var(--slate-400); margin-bottom: 16px;">Silakan buat kategori tiket baru melalui formulir input.</p>
              <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori Tiket</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('modals')
<!-- Modals Detail Fasilitas Benefit per Tiket -->
@foreach ($tickets as $ticket)
  @php
    $badgeClass = match($ticket->tipe_tier) {
      'Early Bird' => 'badge-tier-early',
      'Regular'    => 'badge-tier-regular',
      default      => 'badge-tier-vip',
    };
    $eventName = $events[$ticket->event_id] ?? 'Event Terdaftar';
  @endphp
  <div class="modal-overlay" id="modal-ticket-{{ $ticket->code }}">
    <a href="#" class="modal-backdrop-close" aria-label="Tutup modal"></a>
    <div class="modal-container">
      <div class="modal-header">
        <div style="display: flex; align-items: center; gap: 10px;">
          <h3 class="modal-title">Rincian Kategori Tiket & Benefit</h3>
          <span class="badge {{ $badgeClass }}">{{ $ticket->tipe_tier }}</span>
        </div>
        <a href="#" class="modal-close" title="Tutup">&times;</a>
      </div>
      <div class="modal-body">
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
          <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--slate-900);">{{ $ticket->nama_kategori }}</h2>
          <span style="font-family: monospace; font-weight: 700; color: var(--primary);">{{ $ticket->code }}</span>
        </div>
        <p style="font-size: 0.85rem; color: var(--primary); font-weight: 600; margin-bottom: 6px;">{{ $eventName }}</p>
        <p style="font-size: 0.85rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 16px;">{{ $ticket->deskripsi ?? 'Rincian kategori tiket event.' }}</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 12px 14px; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); margin-bottom: 16px;">
          <div>
            <span style="font-size: 0.725rem; color: var(--slate-500); text-transform: uppercase; font-weight: 600;">Harga Tiket:</span>
            <div style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); font-family: monospace;">{{ $ticket->formatted_price }}</div>
          </div>
          <div>
            <span style="font-size: 0.725rem; color: var(--slate-500); text-transform: uppercase; font-weight: 600;">Periode Penjualan:</span>
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-top: 2px;">{{ $ticket->periode_jual ?? '-' }}</div>
          </div>
        </div>

        <div style="margin-bottom: 20px; padding: 12px 14px; background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-md);">
          <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 6px;">
            <span style="color: var(--slate-600);">Status Kuota: <strong style="color: var(--slate-900);">{{ $ticket->terjual }} Terjual dari {{ $ticket->kuota }} Kuota ({{ $ticket->persen_okupansi }}%)</strong></span>
            <span style="color: var(--primary); font-weight: 600;">Sisa: {{ $ticket->sisa_kuota }} Kursi</span>
          </div>
          <div class="quota-bar-track">
            <div class="quota-bar-fill" style="width: {{ $ticket->persen_okupansi }}%;"></div>
          </div>
        </div>

        <div>
          <h4 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--slate-500); letter-spacing: 0.05em; margin-bottom: 12px;">
            Daftar Hak Fasilitas Benefit Peserta ({{ $ticket->benefits->count() }} Item):
          </h4>
          @if ($ticket->benefits->isEmpty())
            <p style="font-size: 0.85rem; color: var(--slate-400); font-style: italic;">Tidak ada rincian fasilitas tambahan.</p>
          @else
            <div class="benefit-detail-grid">
              @foreach ($ticket->benefits as $idx => $bf)
                <div class="benefit-detail-item">
                  <div class="benefit-detail-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                  </div>
                  <div class="benefit-detail-text">
                    <h5>Fasilitas #{{ $idx + 1 }}</h5>
                    <p>{{ $bf->benefit }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      <div class="modal-footer" style="display:flex; justify-content:space-between; align-items:center;">
        <a href="{{ route('tickets.edit', $ticket->id_tiket) }}" class="btn btn-secondary btn-sm">Edit Data Tiket Ini</a>
        <a href="#" class="btn btn-primary btn-sm">Tutup</a>
      </div>
    </div>
  </div>
@endforeach
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('tableSearchInput');
    const table = document.getElementById('ticketsTable');
    if (!searchInput || !table) return;

    searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(row => {
        if (row.querySelector('.table-empty')) return;
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  });
</script>
@endpush
