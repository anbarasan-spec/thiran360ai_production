<?php
namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceList extends Component
{
    use WithPagination;

    public $search           = '';
    public $checkInDisabled  = false;
    public $checkOutDisabled = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Check In
    public function checkIn()
    {
        $user = Auth::user();
        if ($user->role !== 'user') {
            return;
        }

        $today = Carbon::today()->toDateString();

        $exists = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (! $exists) {
            DB::table('attendances')->insert([
                'user_id'       => $user->id,
                'date'          => $today,
                'check_in_time' => Carbon::now()->format('H:i:s'),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            // Update user status to active
            DB::table('users')
                ->where('id', $user->id)
                ->update(['user_status' => 'active']);
            $this->checkInDisabled = true;
        }
    }

    // Check Out
    public function checkOut()
    {
        $user = Auth::user();
        if ($user->role !== 'user') {
            return;
        }

        $today = Carbon::today()->toDateString();

        $attendance = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && ! $attendance->check_out_time) {
            $checkIn  = Carbon::parse($attendance->check_in_time);
            $checkOut = Carbon::now();

            // Calculate total hours & minutes
            $minutes    = $checkIn->diffInMinutes($checkOut);
            $totalHours = floor($minutes / 60) . ' hrs ' . ($minutes % 60) . ' mins';

            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'check_out_time'      => $checkOut->format('H:i:s'),
                    'total_hours_per_day' => $totalHours,
                    'updated_at'          => now(),
                ]);
            // Update user status to active
            DB::table('users')
                ->where('id', $user->id)
                ->update(['user_status' => 'inactive']);

            $this->checkOutDisabled = true;
        }
    }

    public function render()
    {
        $user = Auth::user();

        $attendances = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select(
                'attendances.id',
                'attendances.date',
                'attendances.check_in_time',
                'attendances.check_out_time',
                'attendances.total_hours_per_day',
                'users.name as user_name'
            )
            ->when($user->role === 'user', fn($q) => $q->where('attendances.user_id', $user->id))
            ->when($this->search, fn($q) => $q->where('users.name', 'like', '%' . $this->search . '%'))
            ->orderBy('attendances.date', 'desc')
            ->paginate(10);

        $today       = Carbon::today()->toDateString();
        $todayRecord = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('livewire.dashboard.attendance-list', [
            'attendances' => $attendances,
            'todayRecord' => $todayRecord,
            'authUser'    => $user,
        ]);
    }
}
