<div>
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog"
            style="background-color: rgba(0,0,0,0.4);">

            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg">
                    <!-- Modal Header -->
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-person-circle me-2"></i> User Profile</h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body py-4">
                        <div class="container-fluid">
                            @if (session()->has('message'))
                                <div class="alert alert-success">{{ session('message') }}</div>
                            @endif

                            <div class="row g-4">
                                <!-- Left Panel: Profile Info -->
                                <div class="col-md-4 text-center">
                                    <div class="position-relative mb-3">
                                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                            class="img-fluid rounded-circle shadow-sm border border-2 border-white" alt="Profile" style="width:150px; height:150px; object-fit:cover;">
                                    </div>

                                    <h4 class="fw-semibold">{{ $name }}</h4>
                                    <p class="text-muted">{{ Auth::user()->role ?? 'User' }}</p>

                                    <div class="d-grid gap-2 mb-3">
                                        <button class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i> Follow</button>
                                        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-chat-left-text me-1"></i> Message</button>
                                    </div>

                                    <ul class="list-unstyled text-start small">
                                        <li><i class="bi bi-globe me-1"></i> <strong>Website:</strong>
                                            <a href="{{ Auth::user()->website ?? '#' }}" target="_blank">{{ Auth::user()->website ?? '-' }}</a>
                                        </li>
                                        <li><i class="bi bi-github me-1"></i> <strong>Github:</strong> {{ Auth::user()->github ?? '-' }}</li>
                                        <li><i class="bi bi-twitter me-1"></i> <strong>Twitter:</strong> {{ Auth::user()->twitter ?? '-' }}</li>
                                        <li><i class="bi bi-instagram me-1"></i> <strong>Instagram:</strong> {{ Auth::user()->instagram ?? '-' }}</li>
                                        <li><i class="bi bi-facebook me-1"></i> <strong>Facebook:</strong> {{ Auth::user()->facebook ?? '-' }}</li>
                                    </ul>
                                </div>

                                <!-- Right Panel: Editable Form -->
                                <div class="col-md-8">
                                    <div class="card shadow-sm border-0 rounded-3 p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">User Details</h5>
                                            <div>
                                                @if ($editing)
                                                    <button class="btn btn-success btn-sm me-1" wire:click="saveProfile">
                                                        <i class="bi bi-check-lg me-1"></i>
                                                    </button>
                                                    <button class="btn btn-secondary btn-sm" wire:click="$set('editing', false)">
                                                        <i class="bi bi-x-lg me-1"></i> 
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-primary btn-sm" wire:click="edit">
                                                        <i class="bi bi-pencil-fill me-1"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>

                                        <!-- Form Fields -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Name</label>
                                            @if ($editing)
                                                <input type="text" class="form-control form-control-sm" wire:model.defer="name">
                                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                            @else
                                                <p class="mb-0">{{ $name }}</p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            @if ($editing)
                                                <input type="email" class="form-control form-control-sm" wire:model.defer="email">
                                                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                            @else
                                                <p class="mb-0">{{ $email }}</p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Phone</label>
                                            @if ($editing)
                                                <input type="text" class="form-control form-control-sm" wire:model.defer="phone_no">
                                                @error('phone_no') <span class="text-danger small">{{ $message }}</span> @enderror
                                            @else
                                                <p class="mb-0">{{ $phone_no ?? '-' }}</p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Designation</label>
                                            @if ($editing)
                                                <input type="text" class="form-control form-control-sm" wire:model.defer="designation">
                                                @error('designation') <span class="text-danger small">{{ $message }}</span> @enderror
                                            @else
                                                <p class="mb-0">{{ $designation ?? '-' }}</p>
                                            @endif
                                        </div>

                                        @if ($editing)
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Password</label>
                                                <input type="password" class="form-control form-control-sm" wire:model.defer="password" placeholder="Leave blank to keep current">
                                                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Modal Body -->
                </div>
            </div>
        </div>
    @endif
</div>
