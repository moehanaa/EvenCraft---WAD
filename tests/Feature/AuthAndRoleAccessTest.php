<?php

namespace Tests\Feature;

use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Akun Admin
        $this->admin = User::create([
            'name'     => 'Alwan Rifky Muhana',
            'email'    => 'admin@eventcraft.com',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
        ]);

        // 2. Setup Akun Staff / Petugas (Role ke-2)
        $this->staff = User::create([
            'name'     => 'Petugas Tiket EventCraft',
            'email'    => 'staff@eventcraft.com',
            'password' => bcrypt('staff123'),
            'role'     => 'staff',
        ]);
    }

    /**
     * 1. KETENTUAN GUEST:
     * Guest (belum login) tidak bisa membuka halaman modul dan diarahkan ke login
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/tickets');
        $response->assertRedirect('/login');

        $responseCreate = $this->get('/tickets/create');
        $responseCreate->assertRedirect('/login');
    }

    /**
     * 2. TAMPILAN LOGIN:
     * Guest dapat melihat formulir login EventCraft
     */
    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('EventCraft System');
        $response->assertSee('admin@eventcraft.com');
        $response->assertSee('staff@eventcraft.com');
    }

    /**
     * 3. PROSES LOGIN:
     * Pengguna dapat login dengan kredensial yang valid
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@eventcraft.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/tickets');
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * 4. PROSES LOGOUT:
     * Pengguna yang sedang login dapat melakukan logout
     */
    public function test_user_can_logout(): void
    {
        $response = $this->actingAs($this->admin)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * 5. HAK AKSES ADMIN:
     * Admin melihat tombol Hapus di halaman tabel dan berhasil menghapus data
     */
    public function test_admin_sees_delete_button_and_can_delete_ticket(): void
    {
        $ticket = TicketCategory::create([
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Tiket Uji Admin',
            'tipe_tier'     => 'VIP',
            'harga'         => 150000,
            'kuota'         => 25,
            'terjual'       => 0,
        ]);

        // Cek halaman index sebagai Admin
        $response = $this->actingAs($this->admin)->get('/tickets');
        $response->assertStatus(200);
        $response->assertSee('Admin');
        $response->assertSee('Hapus Kategori Tiket (Admin Only)'); // Tombol Hapus TERLIHAT

        // Cek eksekusi hapus data berhasil
        $deleteResponse = $this->actingAs($this->admin)->delete("/tickets/{$ticket->id_tiket}");
        $deleteResponse->assertRedirect('/tickets');
        $this->assertDatabaseMissing('tiket_kategori', ['id_tiket' => $ticket->id_tiket]);
    }

    /**
     * 6. HAK AKSES ROLE KE-2 (STAFF / PETUGAS):
     * Staff bisa Lihat, Tambah, Ubah, tetapi TIDAK BISA MELIHAT tombol Hapus
     * dan diblokir (403 Forbidden) jika mencoba melakukan request delete
     */
    public function test_staff_cannot_see_delete_button_and_is_forbidden_from_deleting(): void
    {
        $ticket = TicketCategory::create([
            'event_id'      => 'EVT-001',
            'nama_kategori' => 'Tiket Uji Staff',
            'tipe_tier'     => 'Regular',
            'harga'         => 100000,
            'kuota'         => 50,
            'terjual'       => 0,
        ]);

        // Cek halaman index sebagai Staff
        $response = $this->actingAs($this->staff)->get('/tickets');
        $response->assertStatus(200);
        $response->assertSee('Staff Tiket');
        $response->assertDontSee('Hapus Kategori Tiket (Admin Only)'); // Tombol Hapus TIDAK TERLIHAT

        // Coba request DELETE langsung -> Wajib Ditolak 403 Forbidden!
        $deleteResponse = $this->actingAs($this->staff)->delete("/tickets/{$ticket->id_tiket}");
        $deleteResponse->assertStatus(403);

        // Data di database dipastikan TIDAK terhapus
        $this->assertDatabaseHas('tiket_kategori', ['id_tiket' => $ticket->id_tiket]);
    }
}
