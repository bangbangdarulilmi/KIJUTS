<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\QrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::withCount(['registrations', 'registrations as present_count' => function ($q) {
            $q->where('attendance_status', 'present');
        }]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'event_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'location' => ['required', 'string', 'max:255'],
            'is_online' => ['boolean'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'description' => ['required', 'string'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('events', 'public');
        }

        $event = Event::create([
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'category' => $validated['category'],
            'event_date' => $validated['event_date'],
            'end_date' => $validated['end_date'] ?? null,
            'location' => $validated['location'],
            'is_online' => $request->boolean('is_online'),
            'quota' => $validated['quota'] ?? null,
            'status' => $validated['status'],
            'description' => $validated['description'],
            'banner' => $bannerPath,
            'qr_presensi_token' => 'HMIF-QR-' . strtoupper(Str::random(16)),
            'presensi_open' => false,
        ]);

        return redirect()->route('admin.events.show', $event)->with('success', 'Event berhasil dibuat.');
    }

    public function show(Event $event): View
    {
        $event->load(['registrations.user']);
        
        $qrSvg = null;
        if ($event->qr_presensi_token) {
            $qrSvg = QrService::generateSvg($event->qr_presensi_token);
        }

        return view('admin.events.show', compact('event', 'qrSvg'));
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'event_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'location' => ['required', 'string', 'max:255'],
            'is_online' => ['boolean'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'description' => ['required', 'string'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('events', 'public');
            $event->banner = $bannerPath;
        }

        $event->title = $validated['title'];
        $event->category = $validated['category'];
        $event->event_date = $validated['event_date'];
        $event->end_date = $validated['end_date'] ?? null;
        $event->location = $validated['location'];
        $event->is_online = $request->boolean('is_online');
        $event->quota = $validated['quota'] ?? null;
        $event->status = $validated['status'];
        $event->description = $validated['description'];
        $event->save();

        return redirect()->route('admin.events.show', $event)->with('success', 'Data event berhasil diperbarui.');
    }

    public function togglePresensi(Event $event): RedirectResponse
    {
        $event->update([
            'presensi_open' => ! $event->presensi_open,
        ]);

        $statusText = $event->presensi_open ? 'dibuka' : 'ditutup';
        return back()->with('success', "Presensi kegiatan berhasil {$statusText}.");
    }

    public function markAttendance(Request $request, EventRegistration $registration): RedirectResponse
    {
        $status = $request->input('status', 'present');

        $updateData = ['attendance_status' => $status];

        if ($status === 'present') {
            $updateData['attended_at'] = now();
            if (! $registration->certificate_code) {
                $updateData['certificate_code'] = 'CERT-HMIF-' . date('Y') . '-' . strtoupper(Str::random(8));
                $updateData['certificate_generated_at'] = now();
            }
        } else {
            $updateData['attended_at'] = null;
        }

        $registration->update($updateData);

        return back()->with('success', 'Status presensi peserta berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
