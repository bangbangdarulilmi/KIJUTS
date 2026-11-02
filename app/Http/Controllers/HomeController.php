<?php

namespace App\Http\Controllers;

use App\Models\AcademicRepository;
use App\Models\Article;
use App\Models\Aspiration;
use App\Models\Event;
use App\Models\Member;
use App\Models\StudentProject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $upcomingEvents = Event::where('status', 'upcoming')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        $latestArticles = Article::with('category')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $featuredProjects = StudentProject::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'members_count' => Member::where('status', 'active')->count(),
            'events_count' => Event::where('status', 'completed')->count(),
            'repo_count' => AcademicRepository::count(),
            'projects_count' => StudentProject::where('status', 'approved')->count(),
        ];

        $publicAspirations = Aspiration::where('is_public', true)
            ->latest()
            ->take(4)
            ->get();

        return view('public.home', compact(
            'upcomingEvents',
            'latestArticles',
            'featuredProjects',
            'stats',
            'publicAspirations'
        ));
    }
}
