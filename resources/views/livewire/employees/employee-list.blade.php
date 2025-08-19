<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 fw-bold mb-0">👥 Employees</h2>
        <div class="d-flex gap-2">
            <!-- Search Bar -->
            <div class="input-group shadow-sm" style="max-width: 300px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input wire:model.live="search" type="text" class="form-control border-start-0"
                    placeholder="Search employees">
            </div>
            <!-- Add User Button (Admin only) -->
            @if (auth()->user()->role === 'admin')
                <button wire:click="openAddUserModal" class="btn btn-primary d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-person-plus"></i>
                </button>
            @endif
        </div>
    </div>
    <hr>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show small py-2 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Employee Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Role</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr class="text-center">
                            <td class="fw-semibold">{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone_no }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>₹{{ number_format($employee->salary, 2) }}</td>
                            <td>
                                <span
                                    class="fw-bold text-{{ $employee->user_status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($employee->user_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-{{ $employee->role === 'admin' ? 'primary' : 'info' }}">
                                    {{ ucfirst($employee->role) }}
                                </span>
                            </td>
                            <td>
                                @if (auth()->user()->role === 'admin')
                                    <button wire:click="openEditUserModal({{ $employee->id }})"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                @endif
                                @if (auth()->user()->role === 'admin')
                                    <button wire:click="deleteUser({{ $employee->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-3 border-top">
            {{ $employees->links() }}
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    @if ($showUserModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title">
                            {{ $editMode ? '✏️ Edit User' : '➕ Add New User' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeUserModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" wire:model.defer="name">
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" wire:model.defer="email">
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone No</label>
                                <input type="text" class="form-control" wire:model.defer="phone_no">
                                @error('phone_no')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control" wire:model.defer="designation">
                                @error('designation')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Salary</label>
                                <input type="number" class="form-control" wire:model.defer="salary">
                                @error('salary')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" wire:model.defer="password"
                                    placeholder="{{ $editMode ? 'Leave blank to keep current' : '' }}">
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (auth()->user()->role === 'admin')
                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <select class="form-select" wire:model.defer="role">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" wire:model.defer="user_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('user_status')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" wire:click="closeUserModal">Cancel</button>
                        <button class="btn btn-primary" wire:click="{{ $editMode ? 'updateUser' : 'saveUser' }}">
                            <i class="bi bi-check-circle"></i> {{ $editMode ? 'Update User' : 'Save User' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
