<?php

namespace Tests\Feature;

use App\Models\TicketCategory;
use App\Models\TicketBenefit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);
    }

    /**
     * Test READ: halaman index menampilkan data tiket
     */
    public function test_can_read_tickets_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/tickets');
        $response->assertStatus(200);
        $response->assertSee('Klasifikasi Kategori Tiket');
    }

    /**
     * Test CREATE: tambah kategori tiket dan benefit baru
     */
    public function test_can_create_ticket_with_benefits(): void
    {
        $payload = [
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Developer Test Pass',
            'tipe_tier'     => 'Early Bird',
            'harga'         => 85000,
            'kuota'         => 40,
            'periode_jual'  => '01 Nov 2026 - 15 Nov 2026',
            'deskripsi'     => 'Tiket pengujian otomatis.',
            'benefits'      => [
                'Fasilitas Test 1',
                'Fasilitas Test 2',
            ],
        ];

        $response = $this->actingAs($this->admin)->post('/tickets', $payload);
        $response->assertRedirect('/tickets');

        $this->assertDatabaseHas('tiket_kategori', [
            'nama_kategori' => 'Developer Test Pass',
            'harga'         => 85000,
        ]);

        $ticket = TicketCategory::where('nama_kategori', 'Developer Test Pass')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals(2, $ticket->benefits()->count());
    }

    /**
     * Test UPDATE: perbarui data tiket dan sinkronisasi benefit
     */
    public function test_can_update_ticket_and_sync_benefits(): void
    {
        $ticket = TicketCategory::create([
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Tiket Asli',
            'tipe_tier'     => 'Regular',
            'harga'         => 100000,
            'kuota'         => 50,
            'terjual'       => 10,
            'deskripsi'     => 'Deskripsi awal.',
        ]);

        $ticket->benefits()->create(['benefit' => 'Benefit Lama']);

        $updatePayload = [
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Tiket Diperbarui',
            'tipe_tier'     => 'VIP',
            'harga'         => 200000,
            'kuota'         => 30,
            'deskripsi'     => 'Deskripsi baru.',
            'benefits'      => [
                'Benefit Baru A',
                'Benefit Baru B',
                'Benefit Baru C',
            ],
        ];

        $response = $this->actingAs($this->admin)->put("/tickets/{$ticket->id_tiket}", $updatePayload);
        $response->assertRedirect('/tickets');

        $this->assertDatabaseHas('tiket_kategori', [
            'id_tiket'      => $ticket->id_tiket,
            'nama_kategori' => 'Tiket Diperbarui',
            'tipe_tier'     => 'VIP',
            'harga'         => 200000,
            'kuota'         => 30,
        ]);

        $this->assertEquals(3, $ticket->fresh()->benefits()->count());
        $this->assertDatabaseHas('tiket_benefit', ['benefit' => 'Benefit Baru A']);
    }

    /**
     * Test DELETE: hapus data tiket dan verifikasi cascade deletion
     */
    public function test_can_delete_ticket_and_cascade_benefits(): void
    {
        $ticket = TicketCategory::create([
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Tiket Mau Dihapus',
            'tipe_tier'     => 'Regular',
            'harga'         => 75000,
            'kuota'         => 20,
            'terjual'       => 0,
        ]);

        $ticket->benefits()->create(['benefit' => 'Benefit Terhapus 1']);
        $ticket->benefits()->create(['benefit' => 'Benefit Terhapus 2']);

        $this->assertEquals(2, $ticket->benefits()->count());

        $response = $this->actingAs($this->admin)->delete("/tickets/{$ticket->id_tiket}");
        $response->assertRedirect('/tickets');

        $this->assertDatabaseMissing('tiket_kategori', ['id_tiket' => $ticket->id_tiket]);
        $this->assertDatabaseMissing('tiket_benefit', ['benefit' => 'Benefit Terhapus 1']);
    }
}
