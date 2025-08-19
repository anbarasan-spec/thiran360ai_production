<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 fw-bold mb-0">📅 Attendance</h2>

        <div class="input-group shadow-sm" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input wire:model.live="search" type="text" class="form-control border-start-0"
                placeholder="Search by user name...">
        </div>
    </div>
    <hr>

    @if ($authUser->role === 'user')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                @if (!$todayRecord)
                    <button wire:click="checkIn" class="btn btn-success btn-sm me-2"
                        @if ($checkInDisabled) disabled @endif>
                        <i class="bi bi-box-arrow-in-down-right"></i> Check In
                    </button>
                @elseif ($todayRecord && !$todayRecord->check_out_time)
                    <button wire:click="checkOut" class="btn btn-danger btn-sm me-2"
                        @if ($checkOutDisabled) disabled @endif>
                        <i class="bi bi-box-arrow-up-right"></i> Check Out
                    </button>
                @endif
            </div>

            <!-- Completed Badge -->
            @if ($todayRecord && $todayRecord->check_in_time && $todayRecord->check_out_time)
                <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                    <i class="bi bi-calendar2-check me-1"></i> Completed
                </span>
            @endif
        </div>
        <hr>
    @endif
    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>User</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr class="text-center">
                            <td>{{ $att->user_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                            <td>{{ $att->check_in_time ?? '--' }}</td>
                            <td>{{ $att->check_out_time ?? '--' }}</td>
                            <td>{{ $att->total_hours_per_day ?? '--' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No attendance records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-3 border-top">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
