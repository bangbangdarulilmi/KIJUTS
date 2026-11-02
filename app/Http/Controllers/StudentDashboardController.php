<?php

namespace App\Http\Controllers;

use App\Models\AcademicRepository;
use App\Models\Aspiration;
use App\Models\CodingClinic;
use App\Models\CodingClinicReply;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\StudentProject;
use App\Services\QrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $myRegistrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $certificatesCount = EventRegistration::where('user_id', $user->id)
            ->whereNotNull('certificate_code')
            ->count();

        $myCodingClinics = CodingClinic::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentRepositories = AcademicRepository::latest()
            ->take(5)
            ->get();

        $myProjects = StudentProject::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('student.dashboard', compact(
            'user',
            'myRegistrations',
            'certificatesCount',
            'myCodingClinics',
            'recentRepositories',
            'myProjects'
        ));
    }

    // ------------------------------------------------------------------
    // Event Registrations & E-Certificates
    // ------------------------------------------------------------------
    public function events(): View
    {
        $user = auth()->user();

        $registrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('student.events.index', compact('registrations'));
    }

    public function registerEvent(Request $request, Event $event): RedirectResponse
    {
        $user = auth()->user();

        if ($event->status === 'completed' || $event->status === 'cancelled') {
            return back()->with('error', 'Pendaftaran untuk kegiatan ini sudah ditutup.');
        }

        if ($event->quota && $event->registrations()->count() >= $event->quota) {
            return back()->with('error', 'Mohon maaf, kuota peserta kegiatan ini sudah penuh.');
        }

        $exists = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Anda sudah terdaftar pada kegiatan ini.');
        }

        $code = 'REG-' . strtoupper(Str::random(8)) . '-' . $event->id;

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'registration_code' => $code,
            'attendance_status' => 'registered',
        ]);

        return back()->with('success', 'Selamat, pendaftaran kegiatan berhasil! Kode pendaftaran Anda: ' . $code);
    }

    public function submitAttendance(Request $request): RedirectResponse
    {
        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $event = Event::where('qr_presensi_token', $request->qr_token)->first();

        if (! $event) {
            return back()->with('error', 'Token presensi kegiatan tidak valid.');
        }

        if (! $event->presensi_open) {
            return back()->with('error', 'Presensi untuk kegiatan ini sedang ditutup oleh panitia.');
        }

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $registration) {
            return back()->with('error', 'Anda belum terdaftar pada kegiatan ini.');
        }

        if ($registration->attendance_status === 'present') {
            return back()->with('info', 'Anda sudah melakukan presensi kehadiran sebelumnya.');
        }

        // Generate automated certificate code upon attendance validation
        $certificateCode = 'CERT-HMIF-' . date('Y') . '-' . strtoupper(Str::random(8));

        $registration->update([
            'attendance_status' => 'present',
            'attended_at' => now(),
            'certificate_code' => $certificateCode,
            'certificate_generated_at' => now(),
        ]);

        return back()->with('success', 'Presensi kehadiran berhasil divalidasi! E-Sertifikat Anda telah otomatis diterbitkan.');
    }

    public function viewCertificate(EventRegistration $registration): View|RedirectResponse
    {
        if ($registration->user_id !== auth()->id() && ! auth()->user()->isPengurus()) {
            abort(403);
        }

        if (! $registration->certificate_code) {
            return back()->with('error', 'E-Sertifikat belum tersedia atau kehadiran belum tervalidasi.');
        }

        $registration->load(['event', 'user']);
        $verifyUrl = route('verify.certificate', $registration->certificate_code);
        $qrSvg = QrService::generateSvg($verifyUrl);

        return view('student.events.certificate', compact('registration', 'qrSvg', 'verifyUrl'));
    }

    // ------------------------------------------------------------------
    // Academic Repository & Bank Soal
    // ------------------------------------------------------------------
    public function repository(Request $request): View
    {
        $query = AcademicRepository::with('uploader');

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('course_name', 'like', '%' . $request->search . '%');
            });
        }

        $repositories = $query->latest()->paginate(12);

        return view('student.repository.index', compact('repositories'));
    }

    public function downloadRepository(AcademicRepository $repository): BinaryFileResponse|RedirectResponse
    {
        $repository->increment('download_count');

        if (! Storage::disk('public')->exists($repository->file_path)) {
            return back()->with('error', 'Berkas dokumen tidak ditemukan di server.');
        }

        return response()->download(storage_path('app/public/' . $repository->file_path), $repository->title . '.' . $repository->file_extension);
    }

    public function uploadRepository(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:bank_soal,modul_praktikum,source_code,catatan_kuliah'],
            'course_name' => ['required', 'string', 'max:150'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'exam_type' => ['nullable', 'in:uts,uas,kuis,tugas,other'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,zip,rar,doc,docx,ppt,pptx,txt,png,jpg'],
            'description' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('academic_repo', $fileName, 'public');

        AcademicRepository::create([
            'uploader_id' => auth()->id(),
            'title' => $validated['title'],
            'type' => $validated['type'],
            'course_name' => $validated['course_name'],
            'semester' => $validated['semester'],
            'academic_year' => $validated['academic_year'],
            'exam_type' => $validated['exam_type'] ?? null,
            'file_path' => $filePath,
            'file_size' => round($file->getSize() / 1024, 2) . ' KB',
            'file_extension' => $file->getClientOriginalExtension(),
            'description' => $validated['description'] ?? null,
            'is_verified' => true,
        ]);

        return back()->with('success', 'Materi / bank soal berhasil diunggah ke repositori himpunan!');
    }

    // ------------------------------------------------------------------
    // Dev-Helpdesk / Klinik Koding
    // ------------------------------------------------------------------
    public function clinics(Request $request): View
    {
        $query = CodingClinic::with(['user', 'mentor', 'replies']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('language')) {
            $query->where('programming_language', $request->language);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $clinics = $query->latest()->paginate(10);

        return view('student.clinics.index', compact('clinics'));
    }

    public function storeClinic(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:tugas_kuliah,proyek_mandiri,tugas_akhir,bug_error,general'],
            'programming_language' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'code_snippet' => ['nullable', 'string'],
            'screenshot' => ['nullable', 'image', 'max:4096'],
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('clinics', 'public');
        }

        $clinic = CodingClinic::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'programming_language' => $validated['programming_language'] ?? null,
            'description' => $validated['description'],
            'code_snippet' => $validated['code_snippet'] ?? null,
            'screenshot' => $screenshotPath,
            'status' => 'open',
        ]);

        return redirect()->route('student.clinics.show', $clinic)->with('success', 'Pertanyaan kendala koding berhasil dikirim!');
    }

    public function showClinic(CodingClinic $clinic): View
    {
        $clinic->load(['user', 'mentor', 'replies.user']);

        return view('student.clinics.show', compact('clinic'));
    }

    public function replyClinic(Request $request, CodingClinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'reply' => ['required', 'string'],
            'code_snippet' => ['nullable', 'string'],
        ]);

        CodingClinicReply::create([
            'clinic_id' => $clinic->id,
            'user_id' => auth()->id(),
            'reply' => $validated['reply'],
            'code_snippet' => $validated['code_snippet'] ?? null,
            'is_solution' => false,
        ]);

        if (auth()->user()->isPengurus() && ! $clinic->mentor_id) {
            $clinic->update([
                'mentor_id' => auth()->id(),
                'status' => 'in_progress',
            ]);
        }

        return back()->with('success', 'Tanggapan berhasil dikirimkan.');
    }

    public function markClinicSolution(CodingClinic $clinic, CodingClinicReply $reply): RedirectResponse
    {
        if ($clinic->user_id !== auth()->id() && ! auth()->user()->isPengurus()) {
            abort(403);
        }

        // Reset previous solutions
        $clinic->replies()->update(['is_solution' => false]);
        $reply->update(['is_solution' => true]);

        $clinic->update([
            'status' => 'resolved',
            'solution_notes' => 'Tandai solusi oleh ' . auth()->user()->name,
        ]);

        return back()->with('success', 'Tanggapan telah ditandai sebagai solusi tepat.');
    }

    // ------------------------------------------------------------------
    // Student Projects Showcase
    // ------------------------------------------------------------------
    public function projects(): View
    {
        $projects = StudentProject::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('student.projects.index', compact('projects'));
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tech_stack' => ['required', 'string', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('projects', 'public');
        }

        StudentProject::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'tech_stack' => $validated['tech_stack'],
            'demo_url' => $validated['demo_url'] ?? null,
            'repo_url' => $validated['repo_url'] ?? null,
            'thumbnail' => $thumbnailPath,
            'status' => 'approved', // Auto approved for direct student portfolio showcase
        ]);

        return back()->with('success', 'Karya proyek berhasil dipublikasikan!');
    }

    // ------------------------------------------------------------------
    // Profile & Talent Directory Setting
    // ------------------------------------------------------------------
    public function profile(): View
    {
        $user = auth()->user();
        $member = $user->member ?? Member::firstOrCreate([
            'user_id' => $user->id,
            'nim' => $user->nim ?? 'NIM-' . $user->id,
        ], [
            'name' => $user->name,
            'generation' => date('Y'),
            'status' => 'active',
        ]);

        return view('student.profile', compact('user', 'member'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $member = $user->member ?? Member::firstOrCreate([
            'user_id' => $user->id,
            'nim' => $user->nim ?? 'NIM-' . $user->id,
        ], [
            'name' => $user->name,
            'generation' => date('Y'),
            'status' => 'active',
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'generation' => ['required', 'string', 'max:10'],
            'bio' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
            $member->update(['avatar' => $avatarPath]);
        }

        $member->update([
            'name' => $validated['name'],
            'generation' => $validated['generation'],
            'bio' => $validated['bio'] ?? null,
            'skills' => $validated['skills'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
        ]);

        return back()->with('success', 'Profil dan informasi direktori bakat berhasil diperbarui!');
    }
}
