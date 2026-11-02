<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAspirationController;
use App\Http\Controllers\Admin\AdminCashController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('kegiatan')->name('public.events.')->group(function () {
    Route::get('/', [PublicController::class, 'events'])->name('index');
    Route::get('/{slug}', [PublicController::class, 'eventDetail'])->name('show');
});

Route::prefix('berita')->name('public.articles.')->group(function () {
    Route::get('/', [PublicController::class, 'articles'])->name('index');
    Route::get('/{slug}', [PublicController::class, 'articleDetail'])->name('show');
});

Route::get('/karya-mahasiswa', [PublicController::class, 'projects'])->name('public.projects');
Route::get('/direktori-bakat', [PublicController::class, 'directory'])->name('public.directory');
Route::post('/aspirasi', [PublicController::class, 'submitAspiration'])->name('public.aspiration.submit');
Route::get('/verifikasi-sertifikat/{code}', [PublicController::class, 'verifyCertificate'])->name('verify.certificate');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Student Portal Routes (Mahasiswa Aktif)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa,pengurus,super_admin'])->prefix('portal')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kegiatan', [StudentDashboardController::class, 'events'])->name('events');
    Route::post('/kegiatan/{event}/daftar', [StudentDashboardController::class, 'registerEvent'])->name('events.register');
    Route::post('/presensi', [StudentDashboardController::class, 'submitAttendance'])->name('presensi.submit');
    Route::get('/sertifikat/{registration}', [StudentDashboardController::class, 'viewCertificate'])->name('certificate.view');

    // Repositori Akademik & Bank Soal
    Route::get('/repositori', [StudentDashboardController::class, 'repository'])->name('repository');
    Route::get('/repositori/{repository}/download', [StudentDashboardController::class, 'downloadRepository'])->name('repository.download');
    Route::post('/repositori/upload', [StudentDashboardController::class, 'uploadRepository'])->name('repository.upload');

    // Dev-Helpdesk / Klinik Koding
    Route::get('/klinik-koding', [StudentDashboardController::class, 'clinics'])->name('clinics');
    Route::post('/klinik-koding', [StudentDashboardController::class, 'storeClinic'])->name('clinics.store');
    Route::get('/klinik-koding/{clinic}', [StudentDashboardController::class, 'showClinic'])->name('clinics.show');
    Route::post('/klinik-koding/{clinic}/reply', [StudentDashboardController::class, 'replyClinic'])->name('clinics.reply');
    Route::post('/klinik-koding/{clinic}/solution/{reply}', [StudentDashboardController::class, 'markClinicSolution'])->name('clinics.solution');

    // Portofolio Mahasiswa
    Route::get('/portofolio', [StudentDashboardController::class, 'projects'])->name('projects');
    Route::post('/portofolio', [StudentDashboardController::class, 'storeProject'])->name('projects.store');

    // Profil & Talent Directory
    Route::get('/profil', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::post('/profil', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin & Pengurus Portal Routes (RBAC)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pengurus,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Modul CRUD 1: Manajemen Anggota & Alumni
    Route::resource('members', AdminMemberController::class);

    // Modul CRUD 2: Manajemen Event & Presensi
    Route::resource('events', AdminEventController::class);
    Route::post('events/{event}/toggle-presensi', [AdminEventController::class, 'togglePresensi'])->name('events.toggle_presensi');
    Route::post('events/registrations/{registration}/attendance', [AdminEventController::class, 'markAttendance'])->name('events.mark_attendance');

    // Modul CRUD 3: CMS Artikel & Berita
    Route::resource('articles', AdminArticleController::class);
    Route::post('categories', [AdminArticleController::class, 'storeCategory'])->name('categories.store');

    // Modul CRUD 4: Manajemen Inventaris Himpunan & Peminjaman
    Route::resource('inventories', AdminInventoryController::class);
    Route::get('loans', [AdminInventoryController::class, 'loans'])->name('loans.index');
    Route::post('loans', [AdminInventoryController::class, 'storeLoan'])->name('loans.store');
    Route::post('loans/{loan}/status', [AdminInventoryController::class, 'updateLoanStatus'])->name('loans.update_status');

    // Fitur 6.4: Transparansi Kas Himpunan
    Route::get('cash', [AdminCashController::class, 'index'])->name('cash.index');
    Route::post('cash', [AdminCashController::class, 'store'])->name('cash.store');
    Route::delete('cash/{transaction}', [AdminCashController::class, 'destroy'])->name('cash.destroy');

    // Aspirasi Himpunan
    Route::get('aspirations', [AdminAspirationController::class, 'index'])->name('aspirations.index');
    Route::post('aspirations/{aspiration}/reply', [AdminAspirationController::class, 'reply'])->name('aspirations.reply');
    Route::delete('aspirations/{aspiration}', [AdminAspirationController::class, 'destroy'])->name('aspirations.destroy');

    // Super Admin Only: User Management
    Route::middleware('role:super_admin')->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update_role');
        Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset_password');
    });
});
