<?php
namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class UserDashboard extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = Auth::id(); // ✅ Current logged-in user ID

        // User info (only current user)
        $usersQuery = DB::table('users')
            ->where('id', $userId)
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('designation', 'like', "%{$this->search}%")
            )
            ->orderBy('name');

        // Projects (only for current user)
        $projectsQuery = DB::table('user_projects')
            ->where('user_id', $userId)
            ->when($this->search, fn($q) =>
                $q->where('project_name', 'like', "%{$this->search}%")
            )
            ->orderBy('created_at', 'desc');

        // Attendance (only for current user)
        $attendanceQuery = DB::table('attendances')
            ->where('user_id', $userId)
            ->when($this->search, fn($q) =>
                $q->where('date', 'like', "%{$this->search}%")
            )
            ->orderBy('date', 'desc');

        // Stats for cards (only for current user)
        $stats = [
            'total_projects'     => DB::table('user_projects')->where('user_id', $userId)->count(),
            'active_projects'    => DB::table('user_projects')->where('user_id', $userId)->count(),
            'completed_projects' => DB::table('user_projects')->where('user_id', $userId)->where('project_status', 'complete')->count(),
            'attendance_records' => DB::table('attendances')->where('user_id', $userId)->whereDate('date', today())->count(),
        ];

        // 🔹 Projects grouped by month (last 12 months for this user only)
        $projectsByMonth = DB::table('user_projects')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Prepare arrays for chart.js
        $months = [];
        $totals = [];
        foreach (range(1, 12) as $m) {
            $months[] = date('M', mktime(0, 0, 0, $m, 1));
            $totals[] = $projectsByMonth[$m] ?? 0;
        }

        return view('livewire.dashboard.user-dashboard', [
            'stats'       => $stats,
            'users'       => $usersQuery->paginate(1),
            'projects'    => $projectsQuery->paginate(5, ['*'], 'proj'),
            'attendances' => $attendanceQuery->paginate(5, ['*'], 'att'),
            'months'      => $months,
            'totals'      => $totals,
        ]);
    }
}
