@extends('layouts.app')

@section('title', 'Tambah Kategori Tiket Baru')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Tambah Kategori Tiket Baru</h1>
    <p class="page-subtitle">Isi formulir untuk menambahkan kategori tier tiket dan fasilitas benefit peserta ke database melalui Laravel Controller.</p>
  </div>
  <div>
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      <span>Kembali ke Daftar</span>
    </a>
  </div>
</div>

<form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
  @csrf
  <div class="form-layout">
    <!-- Kolom Kiri: Input Form -->
    <div class="form-main-col">
      
      <!-- Card 1: Informasi Master Event -->
      <div class="card form-card">
        <div class="card-header">
          <h3 class="card-title form-card-title">1. Informasi Tiket & Relasi Master Event</h3>
          <span class="card-subtitle form-card-subtitle">Hubungkan kategori tiket ke event kegiatan yang terdaftar pada Modul A.</span>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label" for="ticketEventSelect">
              Pilih Master Event Terkait <span class="required">*</span>
            </label>
            <select id="ticketEventSelect" name="event_id" class="form-control" required>
              <option value="" disabled selected>-- Pilih Master Event Terkait --</option>
              @foreach ($events as $code => $title)
                <option value="{{ $code }}" {{ old('event_id') == $code ? 'selected' : '' }}>
                  [{{ $code }}] {{ $title }}
                </option>
              @endforeach
            </select>
            <small class="form-hint form-help">Foreign key event_id akan merujuk ke tabel events Modul A.</small>
          </div>

          <div class="form-group">
            <label class="form-label" for="ticketNameInput">
              Nama Kategori / Tier Tiket <span class="required">*</span>
            </label>
            <input type="text" id="ticketNameInput" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" placeholder="Contoh: Early Bird Developer Pass atau VIP Executive" maxlength="100" required>
          </div>

          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="ticketDescInput">
              Deskripsi Ringkas Tiket
            </label>
            <textarea id="ticketDescInput" name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan sasaran peserta, batasan akses, atau catatan khusus tiket ini...">{{ old('deskripsi') }}</textarea>
          </div>
        </div>
      </div>

      <!-- Card 2: Pemilihan Tipe Tier Tiket -->
      <div class="card form-card">
        <div class="card-header">
          <h3 class="card-title form-card-title">2. Klasifikasi Tipe Tier Tiket</h3>
          <span class="card-subtitle form-card-subtitle">Pilih salah satu dari 3 klasifikasi standar industri event.</span>
        </div>
        <div class="card-body">
          <div class="radio-card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
            <!-- Early Bird -->
            <label class="radio-card category-radio-card" style="display:flex; flex-direction:column; cursor:pointer;">
              <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="badge badge-tier-early">Early Bird</span>
                <input type="radio" name="tipe_tier" value="Early Bird" class="tier-radio" {{ old('tipe_tier') == 'Early Bird' ? 'checked' : '' }}>
              </div>
              <div style="font-weight: 700; color: var(--slate-800); margin-top: 10px; font-size: 0.95rem;">Presale Awal</div>
              <p style="font-size: 0.775rem; color: var(--slate-500); margin-top: 4px; line-height: 1.4;">Harga diskon promo sebelum periode penjualan reguler dibuka dengan kuota ketat.</p>
            </label>

            <!-- Regular -->
            <label class="radio-card category-radio-card" style="display:flex; flex-direction:column; cursor:pointer;">
              <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="badge badge-tier-regular">Regular</span>
                <input type="radio" name="tipe_tier" value="Regular" class="tier-radio" {{ old('tipe_tier', 'Regular') == 'Regular' ? 'checked' : '' }}>
              </div>
              <div style="font-weight: 700; color: var(--slate-800); margin-top: 10px; font-size: 0.95rem;">Standard Pass</div>
              <p style="font-size: 0.775rem; color: var(--slate-500); margin-top: 4px; line-height: 1.4;">Tingkatan standar peserta umum dengan fasilitas reguler workshop & seminar.</p>
            </label>

            <!-- VIP -->
            <label class="radio-card category-radio-card" style="display:flex; flex-direction:column; cursor:pointer;">
              <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="badge badge-tier-vip">VIP Pass</span>
                <input type="radio" name="tipe_tier" value="VIP" class="tier-radio" {{ old('tipe_tier') == 'VIP' ? 'checked' : '' }}>
              </div>
              <div style="font-weight: 700; color: var(--slate-800); margin-top: 10px; font-size: 0.95rem;">VIP All-Access</div>
              <p style="font-size: 0.775rem; color: var(--slate-500); margin-top: 4px; line-height: 1.4;">Akses eksklusif, kursi prioritas baris depan, konsultasi privat, dan merchandise.</p>
            </label>
          </div>
        </div>
      </div>

      <!-- Card 3: Penetapan Harga & Kuota Kursi -->
      <div class="card form-card">
        <div class="card-header">
          <h3 class="card-title form-card-title">3. Penetapan Harga Tiket & Alokasi Kuota</h3>
          <span class="card-subtitle form-card-subtitle">Tentukan harga per tiket dan jumlah kursi maksimal yang disediakan.</span>
        </div>
        <div class="card-body">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="ticketPriceInput">
                Harga Tiket (Rupiah) <span class="required">*</span>
              </label>
              <div style="position: relative;">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--slate-400); font-size: 0.85rem;">Rp</span>
                <input type="number" id="ticketPriceInput" name="harga" class="form-control" style="padding-left: 40px;" value="{{ old('harga') }}" placeholder="0" min="0" step="1000" required>
              </div>
              <small class="form-hint form-help">Nominal harga tiket per peserta.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="ticketQuotaInput">
                Alokasi Kuota Kursi <span class="required">*</span>
              </label>
              <div style="position: relative;">
                <input type="number" id="ticketQuotaInput" name="kuota" class="form-control" value="{{ old('kuota') }}" placeholder="Contoh: 50" min="1" required>
                <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.8rem; color: var(--slate-400);">Kursi</span>
              </div>
              <small class="form-hint form-help">Batas kapasitas maksimal tiket tier ini.</small>
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="ticketPeriodInput">
              Rentang Periode Penjualan Tiket
            </label>
            <input type="text" id="ticketPeriodInput" name="periode_jual" class="form-control" value="{{ old('periode_jual') }}" placeholder="Contoh: 01 Okt 2026 - 25 Okt 2026">
          </div>
        </div>
      </div>

      <!-- Card 4: Fasilitas & Hak Benefit Peserta -->
      <div class="card form-card">
        <div class="card-header">
          <h3 class="card-title form-card-title">4. Penyusun Fasilitas & Hak Benefit Peserta</h3>
          <span class="card-subtitle form-card-subtitle">Daftar fasilitas yang berhak dinikmati oleh pemegang tiket kategori ini. Disimpan ke tabel relasional <code>tiket_benefit</code> via Eloquent.</span>
        </div>
        <div class="card-body">
          <!-- Rekomendasi Cepat -->
          <div style="margin-bottom: 16px;">
            <span style="font-size: 0.775rem; font-weight: 600; color: var(--slate-500); text-transform: uppercase;">Pilihan Fasilitas Cepat (Klik untuk tambah):</span>
            <div class="quick-benefit-group">
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Akses Seluruh Sesi Silabus')">+ Akses Seluruh Sesi</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('E-Sertifikat Terverifikasi')">+ E-Sertifikat Terverifikasi</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Slide Materi & Source Code')">+ Handout & Source Code</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Rekaman Video Sesi (30 Hari)')">+ Rekaman Video Sesi</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Snack & Coffee Break')">+ Snack & Coffee Break</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Kursi Prioritas Baris Depan VIP')">+ Kursi Prioritas VIP</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('Merchandise Eksklusif & Tumbler')">+ Merchandise Eksklusif</span>
              <span class="quick-benefit-chip" onclick="addQuickBenefit('1-on-1 Mentoring Privat (30 Menit)')">+ 1-on-1 Mentoring</span>
            </div>
          </div>

          <!-- Kontainer Input Fasilitas Terpilih -->
          <div id="benefitsContainer">
            @if (old('benefits'))
              @foreach (old('benefits') as $oldBf)
                <div class="benefit-row">
                  <span class="benefit-handle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg></span>
                  <input type="text" class="form-control benefit-input" name="benefits[]" value="{{ $oldBf }}" required placeholder="Tuliskan nama fasilitas benefit...">
                  <button type="button" class="btn-remove-benefit" onclick="removeBenefitRow(this)" title="Hapus Fasilitas">&times;</button>
                </div>
              @endforeach
            @else
              <div class="benefit-row">
                <span class="benefit-handle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg></span>
                <input type="text" class="form-control benefit-input" name="benefits[]" value="" required placeholder="Tuliskan nama fasilitas benefit (atau klik pilihan cepat di atas)...">
                <button type="button" class="btn-remove-benefit" onclick="removeBenefitRow(this)" title="Hapus Fasilitas">&times;</button>
              </div>
            @endif
          </div>

          <!-- Tombol Tambah Baris Manual -->
          <button type="button" class="syllabus-btn-add" id="btnAddBenefitRow" onclick="addNewBenefitRow()" style="margin-top: 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Baris Fasilitas Lainnya</span>
          </button>
        </div>
      </div>

    </div>

    <!-- Kolom Kanan: Live Preview & Summary -->
    <div class="form-side-col">
      <div class="summary-box">
        <h3 class="summary-title" style="margin-bottom: 12px;">Live Preview Kartu Tiket</h3>
        <p style="font-size: 0.775rem; color: var(--slate-500); margin-bottom: 16px;">Tampilan kartu tiket dinamis real-time saat Anda mengetik data formulir.</p>

        <!-- Live Card Preview -->
        <div class="ticket-live-card" style="margin-bottom: 20px;">
          <div class="ticket-live-header">
            <div>
              <div class="ticket-live-title" id="previewTitle">Nama Kategori Tiket</div>
              <div class="ticket-live-event" id="previewEvent">Pilih master event terlebih dahulu</div>
            </div>
            <div class="badge badge-tier-regular" id="previewTierBadge">Regular</div>
          </div>

          <div class="ticket-live-body">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px;">
              <div>
                <span style="font-size: 0.7rem; color: var(--slate-400); text-transform: uppercase; font-weight: 600;">Harga Tiket:</span>
                <div class="ticket-live-price" id="previewPrice">Rp 0</div>
              </div>
              <div style="text-align: right;">
                <span style="font-size: 0.7rem; color: var(--slate-400); text-transform: uppercase; font-weight: 600;">Kapasitas Kuota:</span>
                <div style="font-weight: 700; color: var(--slate-800); font-size: 0.9rem;" id="previewQuota">0 Kursi</div>
              </div>
            </div>

            <div style="font-size: 0.75rem; color: var(--slate-500); margin-bottom: 14px;">
              Periode: <strong style="color: var(--slate-700);" id="previewPeriod">-</strong>
            </div>

            <div class="ticket-live-benefits-title">Hak & Fasilitas Peserta:</div>
            <div class="ticket-live-benefit-list" id="previewBenefitList">
              <span style="font-size:0.75rem; color:var(--slate-400); font-style:italic;">Belum ada fasilitas benefit ditambahkan.</span>
            </div>
          </div>

          <div class="ticket-live-footer">
            <span>Potensi Pendapatan Maksimal:</span>
            <strong style="color: var(--primary); font-family: monospace; font-size: 0.9rem;" id="previewTotalRevenue">Rp 0</strong>
          </div>
        </div>

        <!-- Tombol Aksi Form -->
        <div style="display: flex; flex-direction: column; gap: 10px;">
          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <svg viewBox="0 0 24 24">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>Simpan Kategori Tiket (Create)</span>
          </button>

          <a href="{{ route('tickets.index') }}" class="btn btn-secondary" style="width: 100%;">
            <span>Batalkan & Kembali</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
  function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function updatePreview() {
    // 1. Title
    const title = document.getElementById('ticketNameInput').value.trim();
    document.getElementById('previewTitle').textContent = title || 'Nama Kategori Tiket';

    // 2. Event
    const eventSelect = document.getElementById('ticketEventSelect');
    const selectedOption = eventSelect.options[eventSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
      const selectedOptionText = selectedOption.text || '';
      const eventNameClean = selectedOptionText.replace(/^\[.*?\]\s*/, '').replace(/\s*\(.*?\)$/, '');
      document.getElementById('previewEvent').textContent = eventNameClean;
    } else {
      document.getElementById('previewEvent').textContent = 'Pilih master event terlebih dahulu';
    }

    // 3. Tier
    const tierRadios = document.querySelectorAll('input[name="tipe_tier"]');
    let selectedTier = 'Regular';
    tierRadios.forEach(r => {
      if (r.checked) selectedTier = r.value;
    });

    const badgeEl = document.getElementById('previewTierBadge');
    badgeEl.textContent = selectedTier;
    badgeEl.className = 'badge ' + (
      selectedTier === 'Early Bird' ? 'badge-tier-early' : 
      selectedTier === 'Regular' ? 'badge-tier-regular' : 'badge-tier-vip'
    );

    // 4. Price & Quota
    const priceVal = parseFloat(document.getElementById('ticketPriceInput').value) || 0;
    const quotaVal = parseInt(document.getElementById('ticketQuotaInput').value) || 0;
    document.getElementById('previewPrice').textContent = formatRupiah(priceVal);
    document.getElementById('previewQuota').textContent = quotaVal + ' Kursi';

    // 5. Total potential revenue
    const totalRev = priceVal * quotaVal;
    document.getElementById('previewTotalRevenue').textContent = formatRupiah(totalRev);

    // 6. Period
    const period = document.getElementById('ticketPeriodInput').value.trim();
    document.getElementById('previewPeriod').textContent = period || '-';

    // 7. Benefits
    const benefitInputs = document.querySelectorAll('.benefit-input');
    const listEl = document.getElementById('previewBenefitList');
    listEl.innerHTML = '';
    
    let count = 0;
    benefitInputs.forEach(input => {
      const val = input.value.trim();
      if (val !== '') {
        count++;
        const item = document.createElement('div');
        item.className = 'ticket-live-benefit-item';
        item.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>${escapeHtml(val)}</span>`;
        listEl.appendChild(item);
      }
    });

    if (count === 0) {
      listEl.innerHTML = '<span style="font-size:0.75rem; color:var(--slate-400); font-style:italic;">Belum ada fasilitas benefit ditambahkan.</span>';
    }
  }

  function addNewBenefitRow(value = '') {
    const container = document.getElementById('benefitsContainer');
    const row = document.createElement('div');
    row.className = 'benefit-row';
    row.innerHTML = `
      <span class="benefit-handle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg></span>
      <input type="text" class="form-control benefit-input" name="benefits[]" value="${escapeHtml(value)}" required placeholder="Tuliskan nama fasilitas benefit (atau klik pilihan cepat di atas)...">
      <button type="button" class="btn-remove-benefit" onclick="removeBenefitRow(this)" title="Hapus Fasilitas">&times;</button>
    `;
    container.appendChild(row);

    const input = row.querySelector('.benefit-input');
    input.addEventListener('input', updatePreview);
    input.focus();
    updatePreview();
  }

  function removeBenefitRow(button) {
    const container = document.getElementById('benefitsContainer');
    const rows = container.querySelectorAll('.benefit-row');
    if (rows.length > 1) {
      button.closest('.benefit-row').remove();
      updatePreview();
    } else {
      rows[0].querySelector('.benefit-input').value = '';
      updatePreview();
    }
  }

  function addQuickBenefit(text) {
    const inputs = document.querySelectorAll('.benefit-input');
    for (let input of inputs) {
      if (input.value.trim() === '') {
        input.value = text;
        updatePreview();
        return;
      }
    }
    for (let input of inputs) {
      if (input.value.trim() === text) {
        alert('Fasilitas "' + text + '" sudah ada di daftar.');
        return;
      }
    }
    addNewBenefitRow(text);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('#ticketNameInput, #ticketEventSelect, #ticketPriceInput, #ticketQuotaInput, #ticketPeriodInput, .benefit-input');
    inputs.forEach(el => el.addEventListener('input', updatePreview));
    
    document.querySelectorAll('input[name="tipe_tier"]').forEach(r => {
      r.addEventListener('change', updatePreview);
    });

    updatePreview();
  });
</script>
@endpush
