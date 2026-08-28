<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trueque Cash') — Trueque Cash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --tc-gold: #d4a017;
            --tc-gold-dark: #b8860b;
            --tc-gold-light: #f5e6a3;
            --tc-sidebar: #1a1a2e;
            --tc-sidebar-hover: #252545;
        }

        body {
            background-color: #f4f5f7;
            min-height: 100vh;
        }

        .tc-sidebar {
            background: linear-gradient(180deg, var(--tc-sidebar) 0%, #16213e 100%);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
        }

        .tc-sidebar .brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(212, 160, 23, 0.3);
        }

        .tc-sidebar .brand h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--tc-gold);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .tc-sidebar .brand small {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
        }

        .tc-nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.65rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .tc-nav-link:hover,
        .tc-nav-link.active {
            color: #fff;
            background: var(--tc-sidebar-hover);
            border-left-color: var(--tc-gold);
        }

        .tc-nav-link i {
            width: 1.25rem;
            text-align: center;
        }

        .tc-nav-section {
            color: rgba(212, 160, 23, 0.7);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.5rem 0.35rem;
            font-weight: 600;
        }

        .tc-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .tc-topbar {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .tc-content {
            padding: 1.5rem;
        }

        .btn-tc-primary {
            background-color: var(--tc-gold);
            border-color: var(--tc-gold-dark);
            color: #1a1a2e;
            font-weight: 600;
        }

        .btn-tc-primary:hover,
        .btn-tc-primary:focus {
            background-color: var(--tc-gold-dark);
            border-color: var(--tc-gold-dark);
            color: #fff;
        }

        .btn-outline-tc {
            border-color: var(--tc-gold);
            color: var(--tc-gold-dark);
        }

        .btn-outline-tc:hover {
            background-color: var(--tc-gold);
            border-color: var(--tc-gold);
            color: #1a1a2e;
        }

        .card-tc {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border-radius: 0.5rem;
        }

        .card-tc .card-header {
            background: #fff;
            border-bottom: 2px solid var(--tc-gold-light);
            font-weight: 600;
        }

        .stat-card {
            border-left: 4px solid var(--tc-gold);
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--tc-gold-dark);
        }

        .badge-tc {
            background-color: var(--tc-gold);
            color: #1a1a2e;
        }

        .table-tc thead {
            background-color: var(--tc-sidebar);
            color: #fff;
        }

        .table-tc thead th {
            font-weight: 500;
            font-size: 0.85rem;
            border: none;
        }

        .user-badge {
            background: var(--tc-gold-light);
            color: var(--tc-gold-dark);
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .tc-sidebar {
                transform: translateX(-100%);
            }

            .tc-sidebar.show {
                transform: translateX(0);
            }

            .tc-main {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1035;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="tc-sidebar" id="sidebar">
        <div class="brand">
            <h1><i class="bi bi-gem"></i> Trueque Cash</h1>
            <small>Casa de Empeño</small>
        </div>

        <nav class="py-2">
            <a href="{{ route('dashboard') }}" class="tc-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('clientes.index') }}" class="tc-nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clientes
            </a>
            <a href="{{ route('prestamos.index') }}" class="tc-nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Préstamos
            </a>
            <a href="{{ route('pagos.create') }}" class="tc-nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Cobros
            </a>
            <a href="{{ route('remates.index') }}" class="tc-nav-link {{ request()->routeIs('remates.*') ? 'active' : '' }}">
                <i class="bi bi-hammer"></i> Remates
            </a>
            <a href="{{ route('caja.index') }}" class="tc-nav-link {{ request()->routeIs('caja.*') ? 'active' : '' }}">
                <i class="bi bi-safe"></i> Caja
            </a>
            <a href="{{ route('reportes.index') }}" class="tc-nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
            </a>
            <a href="{{ route('notificaciones.index') }}" class="tc-nav-link {{ request()->routeIs('notificaciones.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notificaciones
            </a>

            @if(Auth::user()->isAdmin())
            <div class="tc-nav-section">Administración</div>
            <a href="{{ route('aprobaciones.index') }}" class="tc-nav-link {{ request()->routeIs('aprobaciones.*') ? 'active' : '' }}">
                <i class="bi bi-check2-square"></i> Aprobaciones
            </a>
            <a href="{{ route('parametros.index') }}" class="tc-nav-link {{ request()->routeIs('parametros.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> Parámetros
            </a>
            <a href="{{ route('cotizacion-oro.index') }}" class="tc-nav-link {{ request()->routeIs('cotizacion-oro.*') ? 'active' : '' }}">
                <i class="bi bi-coin"></i> Cotización Oro
            </a>
            <a href="{{ route('auditoria.index') }}" class="tc-nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Auditoría
            </a>
            <a href="{{ route('usuarios.index') }}" class="tc-nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Usuarios
            </a>
            @endif
        </nav>
    </aside>

    <div class="tc-main">
        <header class="tc-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 text-muted d-none d-sm-block">@yield('title', 'Trueque Cash')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold">{{ Auth::user()->nombre_completo }}</div>
                    <span class="user-badge">{{ Auth::user()->rol }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="tc-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        });
        document.getElementById('sidebarOverlay')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
