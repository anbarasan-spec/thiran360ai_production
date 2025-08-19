<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 fw-bold mb-0">📁 Projects</h2>
        <div class="d-flex gap-2">
            <!-- Search -->
            <div class="input-group shadow-sm" style="max-width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input wire:model.live="search" type="text" class="form-control border-start-0"
                    placeholder="Search projects...">
            </div>

            @if (auth()->user()->role === 'admin')
                <button class="btn btn-success d-flex align-items-center gap-1 shadow-sm" wire:click="startCreate">
                    <i class="bi bi-plus-circle"></i>
                </button>
            @endif
        </div>
    </div>
    <hr>

    <!-- Success/Error -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show small py-2 shadow-sm">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show small py-2 shadow-sm">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Projects Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>Project Name</th>
                        <th>User</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Price</th>
                        @endif
                        <th>Status</th>
                        @if (auth()->user()->role === 'admin')
                            <th width="160">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        @if (auth()->user()->role === 'admin' || $project->project_status !== 'canceled')
                            <tr class="text-center">
                                <td class="fw-semibold">{{ $project->project_name }}</td>
                                <td>{{ $project->user_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}</td>

                                @if (auth()->user()->role === 'admin')
                                    <td>₹{{ number_format($project->project_price, 2) }}</td>
                                @endif

                                <td>
                                    @if (auth()->user()->role === 'admin')
                                        <span
                                            class="badge rounded-pill bg-{{ $project->project_status === 'complete' ? 'success' : ($project->project_status === 'in progress' ? 'info' : ($project->project_status === 'pending' ? 'warning text-dark' : 'secondary')) }}">
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    @else
                                        <select class="form-select form-select-sm w-auto d-inline"
                                            wire:change="updateStatus({{ $project->id }}, $event.target.value)">
                                            <option value="pending" @selected($project->project_status == 'pending')>Pending</option>
                                            <option value="in progress" @selected($project->project_status == 'in progress')>In Progress
                                            </option>
                                            <option value="complete" @selected($project->project_status == 'complete')>Complete</option>
                                        </select>
                                    @endif
                                </td>

                                @if (auth()->user()->role === 'admin')
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            wire:click="startEdit({{ $project->id }})">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger"
                                            wire:click="deleteProject({{ $project->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}"
                                class="text-center text-muted py-4">
                                No projects found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">{{ $projects->links() }}</div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade @if ($showPopup) show @endif"
        style="@if ($showPopup) display:block; background-color: rgba(0,0,0,0.3); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $popupMode === 'create' ? 'Add Project' : 'Edit Project' }}</h5>
                    <button type="button" class="btn-close" wire:click="cancel"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Project Name</label>
                        <input type="text" class="form-control" wire:model.defer="editData.project_name">
                        @error('editData.project_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @if (auth()->user()->role === 'admin')
                        <div class="mb-2">
                            <label>User</label>
                            <select class="form-select" wire:model.defer="editData.user_id">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('editData.user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-2">
                        <label>Start Date</label>
                        <input type="date" class="form-control" wire:model.defer="editData.start_date">
                        @error('editData.start_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label>End Date</label>
                        <input type="date" class="form-control" wire:model.defer="editData.end_date">
                        @error('editData.end_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label>Price (₹)</label>
                        <input type="number" class="form-control" wire:model.defer="editData.project_price"
                            step="0.01">
                        @error('editData.project_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label>Status</label>
                        <select class="form-select" wire:model.defer="editData.project_status">
                            <option value="pending">Pending</option>
                            <option value="in progress">In Progress</option>
                            <option value="complete">Complete</option>
                            <option value="canceled">Canceled</option>
                        </select>
                        @error('editData.project_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="cancel">Cancel</button>
                    <button class="btn btn-primary"
                        wire:click="save">{{ $popupMode === 'create' ? 'Create' : 'Update' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
