@extends('layouts.app')

@section('title', 'Detail Kategori Tiket: ' . $ticket->nama_kategori)

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Rincian Tiket: {{ $ticket->nama_kategori }}</h1>
    <p class="page-subtitle">Detail spesifikasi tingkatan tiket, penetapan harga, kapasitas kuota, dan fasilitas benefit peserta (Operasi Read Single).</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="{{ route('tickets.edit', $ticket->id_tiket) }}" class="btn btn-primary btn-sm">Edit Tiket Ini</a>
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
  </div>
</div>

<div class="card form-card" style="max-width: 800px; margin: 0 auto 30px;">
  <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
      <span style="font-family: monospace; font-size: 0.85rem; color: var(--slate-400);">{{ $ticket->code }}</span>
      <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--slate-900); margin-top: 4px;">{{ $ticket->nama_kategori }}</h2>
      <span style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">{{ $events[$ticket->event_id] ?? 'Event Terdaftar' }}</span>
    </div>
    <span class="badge badge-tier-{{ strtolower(str_replace(' ', '', $ticket->tipe_tier)) }}" style="font-size: 0.9rem; padding: 6px 14px;">
      {{ $ticket->tipe_tier }}
    </span>
  </div>

  <div class="card-body">
    <p style="font-size: 0.9rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 20px;">
      {{ $ticket->deskripsi ?? 'Tidak ada deskripsi khusus untuk tiket ini.' }}
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
      <div style="padding: 14px; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md);">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--slate-500); font-weight: 600;">Harga Tiket</span>
        <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary); font-family: monospace; margin-top: 4px;">
          {{ $ticket->formatted_price }}
        </div>
      </div>

      <div style="padding: 14px; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md);">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--slate-500); font-weight: 600;">Alokasi & Okupansi Kuota</span>
        <div style="font-size: 1.25rem; font-weight: 700; color: var(--slate-800); margin-top: 4px;">
          {{ $ticket->terjual }} / {{ $ticket->kuota }} Kursi ({{ $ticket->persen_okupansi }}%)
        </div>
      </div>

      <div style="padding: 14px; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md);">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--slate-500); font-weight: 600;">Periode Penjualan</span>
        <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-700); margin-top: 4px;">
          {{ $ticket->periode_jual ?? '-' }}
        </div>
      </div>
    </div>

    <h4 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--slate-500); letter-spacing: 0.05em; margin-bottom: 12px;">
      Daftar Hak Fasilitas Benefit Peserta ({{ $ticket->benefits->count() }} Fasilitas):
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

  <div class="card-footer" style="padding: 18px 24px; background: var(--slate-50); border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
    <div style="display:flex; gap:10px;">
      <a href="{{ route('tickets.edit', $ticket->id_tiket) }}" class="btn btn-primary btn-sm">Edit Data Tiket</a>
      <form action="{{ route('tickets.destroy', $ticket->id_tiket) }}" method="POST" onsubmit="return confirm('Hapus kategori tiket ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Hapus Tiket</button>
      </form>
    </div>
  </div>
</div>
@endsection
