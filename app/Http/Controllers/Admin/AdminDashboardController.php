<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Aspiration;
use App\Models\CashTransaction;
use App\Models\CodingClinic;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\InventoryLoan;
use App\Models\Member;
use App\Models\StudentProject;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalMembers = Member::where('status', 'active')->count();
        $totalAlumni = Member::where('status', 'alumni')->count();
        $upcomingEventsCount = Event::where('status', 'upcoming')->count();
        
        $totalIncome = CashTransaction::where('type', 'income')->sum('amount');
        $totalExpense = CashTransaction::where('type', 'expense')->sum('amount');
        $cashBalance = $totalIncome - $totalExpense;

        $pendingLoansCount = InventoryLoan::where('status', 'pending')->count();
        $pendingClinicsCount = CodingClinic::where('status', 'open')->count();
        $unreadAspirationsCount = Aspiration::where('status', 'unread')->count();

        // Recent registrations
        $recentRegistrations = EventRegistration::with(['event', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Recent cash transactions
        $recentTransactions = CashTransaction::latest('transaction_date')
            ->take(5)
            ->get();

        // Pending loans
        $pendingLoans = InventoryLoan::with(['inventory', 'user'])
            ->where('status', 'pending')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalAlumni',
            'upcomingEventsCount',
            'totalIncome',
            'totalExpense',
            'cashBalance',
            'pendingLoansCount',
            'pendingClinicsCount',
            'unreadAspirationsCount',
            'recentRegistrations',
            'recentTransactions',
            'pendingLoans'
        ));
    }
}
