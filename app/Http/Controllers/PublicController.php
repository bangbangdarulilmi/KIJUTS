<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\StudentProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    // Events
    public function events(Request $request): View
    {
        $query = Event::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(9);

        return view('public.events.index', compact('events'));
    }

    public function eventDetail(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        
        $userRegistration = null;
        if (auth()->check()) {
            $userRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->first();
        }

        $relatedEvents = Event::where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->take(3)
            ->get();

        return view('public.events.show', compact('event', 'userRegistration', 'relatedEvents'));
    }

    // Articles & News
    public function articles(Request $request): View
    {
        $query = Article::with(['category', 'author'])->where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(9);
        $categories = Category::withCount('articles')->get();

        return view('public.articles.index', compact('articles', 'categories'));
    }

    public function articleDetail(string $slug): View
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $article->increment('views');

        $recentArticles = Article::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        return view('public.articles.show', compact('article', 'recentArticles'));
    }

    // Showcase Projects
    public function projects(Request $request): View
    {
        $query = StudentProject::with('user')->where('status', 'approved');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('tech_stack', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(9);

        return view('public.projects.index', compact('projects'));
    }

    // Talent Directory
    public function directory(Request $request): View
    {
        $query = Member::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('generation')) {
            $query->where('generation', $request->generation);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%')
                  ->orWhere('skills', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->orderBy('generation', 'desc')->orderBy('name', 'asc')->paginate(12);
        $generations = Member::select('generation')->distinct()->orderBy('generation', 'desc')->pluck('generation');

        return view('public.directory.index', compact('members', 'generations'));
    }

    // Aspirasi Submission
    public function submitAspiration(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sender_name' => ['required', 'string', 'max:100'],
            'sender_email' => ['required', 'email', 'max:100'],
            'subject' => ['required', 'string', 'max:200'],
            'category' => ['required', 'in:fasilitas,akademik,kegiatan_himpunan,kritik_saran,lainnya'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        Aspiration::create([
            'user_id' => auth()->id(),
            'sender_name' => $validated['sender_name'],
            'sender_email' => $validated['sender_email'],
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'is_public' => false,
            'status' => 'unread',
        ]);

        return back()->with('success', 'Aspirasi / pesan Anda telah terkirim. Terima kasih atas partisipasi Anda!');
    }

    // Certificate Verification Page (QR Scan Destination)
    public function verifyCertificate(string $code): View
    {
        $registration = EventRegistration::with(['event', 'user'])
            ->where('certificate_code', $code)
            ->first();

        return view('public.verify_certificate', compact('registration', 'code'));
    }
}
