<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @livewireStyles
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            transition: background 0.3s, color 0.3s;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar .nav-link {
            color: #333;
            font-weight: 500;
            margin: 6px 10px;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            height: 60px;
            background: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            transition: background 0.3s, color 0.3s;
        }

        .header .search-bar {
            width: 50%;
        }

        /* Content */
        .content {
            margin-left: 240px;
            margin-top: 70px;
            padding: 20px;
            min-height: calc(100vh - 110px);
            transition: background 0.3s, color 0.3s;
        }

        /* Footer */
        footer {
            position: fixed;
            bottom: 0;
            left: 240px;
            /* offset for sidebar */
            width: calc(100% - 240px);
            /* full width minus sidebar */
            background: #f1f1f1;
            border-top: 1px solid #dee2e6;
            text-align: center;
            padding: 10px;
            transition: background 0.3s, color 0.3s;
        }


        /* Dark theme */
        body.dark-theme {
            background: #121212;
            color: #f8f9fa;
        }

        body.dark-theme .sidebar {
            background: #1e1e1e;
            border-right: 1px solid #333;
        }

        body.dark-theme .sidebar .nav-link {
            color: #f8f9fa;
        }

        body.dark-theme .sidebar .nav-link:hover,
        body.dark-theme .sidebar .nav-link.active {
            background-color: #2c2c2c;
            color: #0d6efd;
        }

        body.dark-theme .header {
            background: #0a58ca;
            color: white;
        }

        body.dark-theme .content {
            background: #1a1a1a;
            color: #f8f9fa;
        }

        body.dark-theme footer {
            background: #1a1a1a;
            color: #f8f9fa;
            border-top: 1px solid #333;
        }

        #theme-toggle {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h4 class="text-center text-primary mb-4">THIRAN360AI</h4>
        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('employees') }}" class="nav-link {{ request()->routeIs('employees') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Employees
            </a>
            <a href="{{ route('projects') }}" class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }}">
                <i class="bi bi-kanban me-2"></i> Projects
            </a>
            <a href="{{ route('attendance.list') }}"
                class="nav-link {{ request()->routeIs('attendance.list') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Attendance
            </a>
        </nav>
    </div>

    <!-- Header -->
    <div class="header">
        <form class="d-flex search-bar">
            <input wire:model.live="search" class="form-control" type="search" placeholder="Search..."
                aria-label="Search">
        </form>

        <div class="d-flex align-items-center">
            <i class="bi bi-bell me-3 fs-5"></i>

            <button id="theme-toggle" class="btn btn-link text-white p-0 me-3">
                <i class="bi bi-moon-fill fs-5"></i>
            </button>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D6EFD&color=fff&size=32"
                        alt="{{ auth()->user()->name }}" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ auth()->user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <div class="content">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <footer>
        <p class="mb-0">© {{ date('Y') }} THIRAN360AI - All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const body = document.body;

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-theme');

            const icon = toggleBtn.querySelector('i');
            if (body.classList.contains('dark-theme')) {
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
                toggleBtn.classList.replace('btn-outline-light', 'btn-outline-dark');
            } else {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-fill');
                toggleBtn.classList.replace('btn-outline-dark', 'btn-outline-light');
            }
        });
    </script>
</body>

</html>
