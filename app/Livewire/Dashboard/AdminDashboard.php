<?php
namespace App\Livewire\Dashboard;

use App\Models\Attendance;
use App\Models\User;
use App\Models\UserProject;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
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
        // Users with search filter
        $usersQuery = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('designation', 'like', "%{$this->search}%")
            )
            ->orderBy('name');

        // Projects with search filter
        $projectsQuery = UserProject::with('user')
            ->when($this->search, fn($q) =>
                $q->where('project_name', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"))
            )
            ->latest();

        // Attendance with search filter
        $attendanceQuery = Attendance::with('user')
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"))
            )
            ->latest('date');

        // Stats for cards
        $stats = [
            'total_employees'    => User::where('role', 'user')->count(),
            'active_projects'    => UserProject::count(),
            'completed_projects' => UserProject::where('project_status', 'complete')->count(),
            'attendance_records' => Attendance::whereDate('date', today())->count(),
        ];

        // 🔹 Projects grouped by month (last 6 months)
        $projectsByMonth = UserProject::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Prepare arrays for chart.js
        $months = [];
        $totals = [];
        foreach (range(1, 12) as $m) {
            $months[] = date('M', mktime(0, 0, 0, $m, 1));
            $totals[] = $projectsByMonth[$m] ?? 0;
        }

        return view('livewire.dashboard.admin-dashboard', [
            'stats'       => $stats,
            'users'       => $usersQuery->paginate(5),
            'projects'    => $projectsQuery->paginate(5, pageName: 'proj'),
            'attendances' => $attendanceQuery->paginate(5, pageName: 'att'),
            'months'      => $months,
            'totals'      => $totals,
        ]);
    }

}
