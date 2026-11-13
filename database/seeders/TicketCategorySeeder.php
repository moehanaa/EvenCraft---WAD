<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use App\Models\TicketBenefit;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            [
                'event_id'      => 'EVT-001',
                'nama_kategori' => 'Early Bird Developer Pass',
                'tipe_tier'     => 'Early Bird',
                'harga'         => 99000.00,
                'kuota'         => 30,
                'terjual'       => 28,
                'deskripsi'     => 'Presale diskon 45% khusus mahasiswa & junior developer.',
                'periode_jual'  => '01 Okt 2026 - 10 Okt 2026',
                'benefits'      => [
                    'Akses Seluruh Sesi Workshop',
                    'E-Sertifikat Terverifikasi',
                    'Slide Materi & Source Code Repository',
                    'Grup Diskusi Telegram Eksklusif',
                ],
            ],
            [
                'event_id'      => 'EVT-001',
                'nama_kategori' => 'Regular Developer Standard',
                'tipe_tier'     => 'Regular',
                'harga'         => 175000.00,
                'kuota'         => 75,
                'terjual'       => 45,
                'deskripsi'     => 'Tiket reguler pelatihan teknis backend RESTful API.',
                'periode_jual'  => '11 Okt 2026 - 25 Okt 2026',
                'benefits'      => [
                    'Akses Seluruh Sesi Workshop & Hands-on Lab',
                    'E-Sertifikat Terverifikasi',
                    'Slide Materi & Source Code',
                    'Rekaman Video Sesi (Akses 30 Hari)',
                    'Snack & Lunch Box',
                ],
            ],
            [
                'event_id'      => 'EVT-001',
                'nama_kategori' => 'VIP Executive & Mentoring Pass',
                'tipe_tier'     => 'VIP',
                'harga'         => 350000.00,
                'kuota'         => 20,
                'terjual'       => 18,
                'deskripsi'     => 'Akses all-access, kursi depan VIP, dan sesi konsultasi privat 1-on-1.',
                'periode_jual'  => '01 Okt 2026 - 25 Okt 2026',
                'benefits'      => [
                    'Semua Fasilitas Tiket Regular',
                    'Kursi Prioritas Baris Depan (VIP Front Row)',
                    '1-on-1 Code Review & Konsultasi Privat (30 Menit)',
                    'Exclusive Developer Hoodie & Tumbler EventCraft',
                    'Networking Lunch bersama Pemateri Utama & VCs',
                ],
            ],
            [
                'event_id'      => 'EVT-002',
                'nama_kategori' => 'Regular Founder Standard',
                'tipe_tier'     => 'Regular',
                'harga'         => 210000.00,
                'kuota'         => 80,
                'terjual'       => 50,
                'deskripsi'     => 'Tiket standar seminar valuasi bisnis startup dan financial runway.',
                'periode_jual'  => '16 Okt 2026 - 28 Okt 2026',
                'benefits'      => [
                    'Financial Model Spreadsheet Template',
                    'Snack & Networking Coffee Break',
                    'Akses Tanya Jawab Interaktif',
                ],
            ],
            [
                'event_id'      => 'EVT-003',
                'nama_kategori' => 'Regular Cloud Engineer Pass',
                'tipe_tier'     => 'Regular',
                'harga'         => 250000.00,
                'kuota'         => 60,
                'terjual'       => 22,
                'deskripsi'     => 'Tiket praktikum teknis hands-on cloud orchestration dengan cluster Kubernetes.',
                'periode_jual'  => '10 Okt 2026 - 30 Okt 2026',
                'benefits'      => [
                    'Akses Hands-on Cloud Lab Environment (3 Hari)',
                    'Modul Praktikum & Manifest YAML K8s',
                    'E-Sertifikat Pelatihan',
                ],
            ],
        ];

        foreach ($tickets as $data) {
            $benefits = $data['benefits'];
            unset($data['benefits']);

            $ticket = TicketCategory::create($data);

            foreach ($benefits as $b) {
                TicketBenefit::create([
                    'id_tiket' => $ticket->id_tiket,
                    'benefit'  => $b,
                ]);
            }
        }
    }
}
