<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class HmifPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('HMIF');
        $response->assertSee('STT Cipasung');
    }

    public function test_public_pages_accessible(): void
    {
        $this->get('/kegiatan')->assertStatus(200);
        $this->get('/berita')->assertStatus(200);
        $this->get('/karya-mahasiswa')->assertStatus(200);
        $this->get('/direktori-bakat')->assertStatus(200);
    }

    public function test_student_registration_creates_user_and_member(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fajar Santoso',
            'nim' => '2101099',
            'email' => 'fajar@sttcipasung.ac.id',
            'generation' => '2022',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('users', [
            'nim' => '2101099',
            'email' => 'fajar@sttcipasung.ac.id',
            'role' => 'mahasiswa',
        ]);
        $this->assertDatabaseHas('members', [
            'nim' => '2101099',
            'generation' => '2022',
        ]);
    }

    public function test_user_can_login_with_nim_and_with_email(): void
    {
        $user = User::create([
            'name' => 'Ahmad Fauzi',
            'nim' => '2201050',
            'email' => 'ahmad@sttcipasung.ac.id',
            'role' => 'mahasiswa',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        // Login with NIM
        $responseNim = $this->post('/login', [
            'login_id' => '2201050',
            'password' => 'password123',
        ]);
        $responseNim->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);

        auth()->logout();

        // Login with Email
        $responseEmail = $this->post('/login', [
            'login_id' => 'ahmad@sttcipasung.ac.id',
            'password' => 'password123',
        ]);
        $responseEmail->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_event_registration_and_qr_attendance_generates_certificate(): void
    {
        $student = User::create([
            'name' => 'Siti Nurhaliza',
            'nim' => '2201088',
            'email' => 'siti@sttcipasung.ac.id',
            'role' => 'mahasiswa',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        $event = Event::create([
            'title' => 'Workshop AI & Agentic Coding',
            'slug' => 'workshop-ai',
            'description' => 'Pelatihan AI terkini untuk mahasiswa',
            'category' => 'workshop',
            'event_date' => now()->addDays(2),
            'location' => 'Lab Informatika STT Cipasung',
            'is_online' => false,
            'quota' => 50,
            'status' => 'upcoming',
            'qr_presensi_token' => 'HMIF-QR-TEST1234',
            'presensi_open' => true,
        ]);

        // Student registers
        $this->actingAs($student)->post(route('student.events.register', $event->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $student->id,
            'attendance_status' => 'registered',
        ]);

        // Student submits presence with QR token
        $this->actingAs($student)->post(route('student.presensi.submit'), [
            'qr_token' => 'HMIF-QR-TEST1234',
        ])->assertSessionHas('success');

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $student->id)
            ->first();

        $this->assertEquals('present', $registration->attendance_status);
        $this->assertNotNull($registration->certificate_code);

        // Certificate verification page works
        $verifyResponse = $this->get(route('verify.certificate', $registration->certificate_code));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($student->name);
        $verifyResponse->assertSee('Verifikasi E-Sertifikat HMIF');
    }

    public function test_rbac_protects_admin_panel(): void
    {
        $student = User::create([
            'name' => 'Mahasiswa Biasa',
            'nim' => '2301011',
            'email' => 'mhs@sttcipasung.ac.id',
            'role' => 'mahasiswa',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        // Guest redirected to login
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        // Mahasiswa gets 403 Forbidden
        $this->actingAs($student)->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_artisan_create_admin_command(): void
    {
        $this->artisan('hmif:create-admin', [
            '--name' => 'Koor Medinfo',
            '--email' => 'koor.medinfo@sttcipasung.ac.id',
            '--nim' => 'MEDINFO01',
            '--password' => 'secretadmin',
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'koor.medinfo@sttcipasung.ac.id',
            'role' => 'super_admin',
        ]);
    }
}
