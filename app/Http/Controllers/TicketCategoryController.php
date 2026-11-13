<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use App\Models\TicketBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketCategoryController extends Controller
{
    /**
     * Daftar Master Event (Koleksi Modul A)
     */
    protected array $events = [
        'EVT-001' => 'Workshop RESTful API Architecture dengan Laravel 11 (Teknologi)',
        'EVT-002' => 'Seminar Strategi Valuasi & Inovasi Model Bisnis Startup Digital (Bisnis)',
        'EVT-003' => 'Pelatihan Cloud Infrastructure & Kubernetes Container Orchestration (Teknologi)',
    ];

    /**
     * [READ ALL] Menampilkan daftar kategori tiket & kalkulasi statistik
     */
    public function index()
    {
        // Eager loading relasi benefits
        $tickets = TicketCategory::with('benefits')->orderBy('id_tiket', 'asc')->get();

        // Kalkulasi KPI statistik secara dinamis
        $totalKategori   = $tickets->count();
        $totalTerjual    = $tickets->sum('terjual');
        $totalKuota      = $tickets->sum('kuota');
        $persenOkupansi = ($totalKuota > 0) ? round(($totalTerjual / $totalKuota) * 100) : 0;
        $totalPendapatan = $tickets->sum(fn($t) => $t->terjual * $t->harga);
        $rataRataHarga   = ($totalKategori > 0) ? round($tickets->avg('harga')) : 0;

        $events = $this->events;

        return view('tickets.index', compact(
            'tickets',
            'totalKategori',
            'totalTerjual',
            'totalKuota',
            'persenOkupansi',
            'totalPendapatan',
            'rataRataHarga',
            'events'
        ));
    }

    /**
     * [CREATE VIEW] Menampilkan formulir tambah tiket baru
     */
    public function create()
    {
        $events = $this->events;
        return view('tickets.create', compact('events'));
    }

    /**
     * [CREATE ACTION] Menyimpan kategori tiket dan fasilitas benefit ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id'      => 'required|string|max:20',
            'nama_kategori' => 'required|string|max:100',
            'tipe_tier'     => 'required|in:Early Bird,Regular,VIP',
            'harga'         => 'required|numeric|min:0',
            'kuota'         => 'required|integer|min:1',
            'periode_jual'  => 'nullable|string|max:100',
            'deskripsi'     => 'nullable|string',
            'benefits'      => 'nullable|array',
            'benefits.*'    => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, &$ticket) {
            $ticket = TicketCategory::create([
                'event_id'      => $validated['event_id'],
                'nama_kategori' => $validated['nama_kategori'],
                'tipe_tier'     => $validated['tipe_tier'],
                'harga'         => $validated['harga'],
                'kuota'         => $validated['kuota'],
                'terjual'       => 0,
                'periode_jual'  => $validated['periode_jual'] ?? null,
                'deskripsi'     => $validated['deskripsi'] ?? null,
            ]);

            if (!empty($validated['benefits'])) {
                foreach ($validated['benefits'] as $bText) {
                    $cleaned = trim($bText ?? '');
                    if ($cleaned !== '') {
                        $ticket->benefits()->create(['benefit' => $cleaned]);
                    }
                }
            }
        });

        return redirect()->route('tickets.index')->with('success', "Kategori tiket '{$ticket->nama_kategori}' berhasil ditambahkan ke database!");
    }

    /**
     * [READ SINGLE] Menampilkan detail spesifik kategori tiket
     */
    public function show(string $id)
    {
        $ticket = TicketCategory::with('benefits')->findOrFail($id);
        $events = $this->events;
        return view('tickets.show', compact('ticket', 'events'));
    }

    /**
     * [UPDATE VIEW] Menampilkan formulir edit kategori tiket
     */
    public function edit(string $id)
    {
        $ticket = TicketCategory::with('benefits')->findOrFail($id);
        $events = $this->events;
        return view('tickets.edit', compact('ticket', 'events'));
    }

    /**
     * [UPDATE ACTION] Memperbarui data kategori tiket dan sinkronisasi benefit
     */
    public function update(Request $request, string $id)
    {
        $ticket = TicketCategory::findOrFail($id);

        $validated = $request->validate([
            'event_id'      => 'required|string|max:20',
            'nama_kategori' => 'required|string|max:100',
            'tipe_tier'     => 'required|in:Early Bird,Regular,VIP',
            'harga'         => 'required|numeric|min:0',
            'kuota'         => 'required|integer|min:1',
            'periode_jual'  => 'nullable|string|max:100',
            'deskripsi'     => 'nullable|string',
            'benefits'      => 'nullable|array',
            'benefits.*'    => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($ticket, $validated) {
            $ticket->update([
                'event_id'      => $validated['event_id'],
                'nama_kategori' => $validated['nama_kategori'],
                'tipe_tier'     => $validated['tipe_tier'],
                'harga'         => $validated['harga'],
                'kuota'         => $validated['kuota'],
                'periode_jual'  => $validated['periode_jual'] ?? null,
                'deskripsi'     => $validated['deskripsi'] ?? null,
            ]);

            // Sinkronisasi data benefit: hapus lama dan simpan yang baru
            $ticket->benefits()->delete();

            if (!empty($validated['benefits'])) {
                foreach ($validated['benefits'] as $bText) {
                    $cleaned = trim($bText ?? '');
                    if ($cleaned !== '') {
                        $ticket->benefits()->create(['benefit' => $cleaned]);
                    }
                }
            }
        });

        return redirect()->route('tickets.index')->with('success', "Kategori tiket '{$ticket->nama_kategori}' berhasil diperbarui!");
    }

    /**
     * [DELETE ACTION] Menghapus kategori tiket dan seluruh relasi benefitnya (Khusus Admin)
     */
    public function destroy(string $id)
    {
        // Proteksi Otorisasi Tambahan di Controller
        if (auth()->check() && auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya pengguna dengan Role Administrator yang diizinkan menghapus data kategori tiket.');
        }

        $ticket = TicketCategory::findOrFail($id);
        $nama = $ticket->nama_kategori;
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', "Kategori tiket '{$nama}' berhasil dihapus dari database!");
    }
}
