<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pelaporan Produksi')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        /* ============================================
           SEMENTARA - SEMUA CSS DI SINI
           ============================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hanken Grotesk', sans-serif; background: #f7f9fe; }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 248px;
            height: 100vh;
            background: #0f1b35;
            color: #94a3b8;
            z-index: 1050;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand { padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .sidebar-brand a { text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; border-radius: 0.5rem;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;
        }
        .sidebar-brand .brand-text { color: white; font-weight: 700; font-size: 16px; }
        .sidebar-brand .brand-sub { color: #94a3b8; font-size: 12px; }
        
        .sidebar-nav { flex: 1; padding: 12px; }
        .sidebar-nav .nav-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
            color: #94a3b8; text-transform: uppercase; padding: 16px 12px 8px; opacity: 0.6;
        }
        .sidebar-nav .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            border-radius: 0.5rem; color: #94a3b8; text-decoration: none;
            font-size: 14px; font-weight: 500; margin-bottom: 2px;
            cursor: pointer; transition: all 0.2s;
        }
        .sidebar-nav .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-nav .nav-item.active { background: #1d4ed8; color: white; box-shadow: 0 4px 12px rgba(29,78,216,0.35); }
        .sidebar-nav .nav-item .nav-icon { font-size: 18px; width: 22px; text-align: center; }
        
        .sidebar-footer { padding: 12px 16px 20px; border-top: 1px solid rgba(255,255,255,0.06); }
        .sidebar-footer .nav-item { width: 100%; background: none; border: none; text-align: left; font-family: inherit; font-size: inherit; padding: 10px 14px; color: #94a3b8; }
        .sidebar-footer .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        
        /* Main Content */
        .main-content { margin-left: 248px; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Top Navbar */
        .top-navbar {
            height: 56px; background: white; border-bottom: 1px solid #e2e3e0;
            display: flex; align-items: center; justify-content: space-between; padding: 0 24px;
            position: sticky; top: 0; z-index: 1040;
        }
        .top-navbar .navbar-left { display: flex; align-items: center; gap: 16px; }
        .top-navbar .navbar-left .page-title { font-size: 18px; font-weight: 600; color: #1e293b; margin: 0; }
        .top-navbar .navbar-right { display: flex; align-items: center; gap: 8px; }
        .top-navbar .navbar-right .user-menu {
            display: flex; align-items: center; gap: 10px; padding: 6px 12px 6px 6px;
            border-radius: 9999px; cursor: pointer; text-decoration: none; color: #1e293b;
        }
        .top-navbar .navbar-right .user-menu .avatar {
            width: 32px; height: 32px; border-radius: 9999px; background: #1d4ed8;
            color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px;
        }
        .top-navbar .navbar-right .user-menu .user-role { font-size: 12px; color: #434655; }
        
        .sidebar-toggle {
            display: none; background: none; border: none; color: #1e293b; font-size: 24px; padding: 8px; cursor: pointer;
        }
        .sidebar-overlay { display: none; }
        
        /* Page Content */
        .page-content { padding: 24px; flex: 1; }
        
        /* Cards */
        .card { background: white; border: 1px solid #e2e3e0; border-radius: 1rem; margin-bottom: 20px; }
        .card .card-header { background: transparent; border-bottom: 1px solid #e2e3e0; padding: 16px 20px; font-weight: 600; font-size: 14px; color: #1e293b; }
        .card .card-body { padding: 20px; }
        .card .card-body.p-0 { padding: 0; }
        
        /* Stat Cards */
        .stat-card { background: white; border: 1px solid #e2e3e0; border-radius: 1rem; padding: 20px 24px; }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 12px; }
        .stat-card .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-card .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-card .stat-icon.yellow { background: #fef3c7; color: #d97706; }
        .stat-card .stat-icon.red { background: #fee2e2; color: #dc2626; }
        .stat-card .stat-label { font-size: 12px; color: #434655; font-weight: 500; margin-bottom: 4px; }
        .stat-card .stat-value { font-size: 24px; font-weight: 700; color: #1e293b; line-height: 32px; }
        
        /* Tables */
        .table-container { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .table thead th { background: #f2f4f9; color: #434655; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 10px 14px; border-bottom: 1px solid #e2e3e0; text-align: left; }
        .table tbody td { padding: 10px 14px; border-bottom: 1px solid #e2e3e0; vertical-align: middle; }
        .table tbody tr:hover { background: #f2f4f9; }
        
        /* Badges */
        .badge-custom { font-family: 'Hanken Grotesk', sans-serif; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 9999px; display: inline-block; text-align: center; }
        .badge-custom.badge-success { background: #dcfce7; color: #16a34a; }
        .badge-custom.badge-warning { background: #fef3c7; color: #d97706; }
        .badge-custom.badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-custom.badge-info { background: #dbeafe; color: #2563eb; }
        .badge-custom.badge-secondary { background: #e6e8ed; color: #434655; }
        
        /* Buttons */
        .btn { font-family: 'Hanken Grotesk', sans-serif; font-size: 12px; font-weight: 500; padding: 8px 18px; border-radius: 0.5rem; border: 1px solid transparent; transition: all 0.2s; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .btn-primary { background: #0037b0; color: white; border-color: #0037b0; }
        .btn-primary:hover { background: #0037b0; color: white; }
        .btn-secondary { background: #e6e8ed; color: #1e293b; border-color: #e6e8ed; }
        .btn-secondary:hover { background: #d8dadf; }
        .btn-warning { background: #d97706; color: white; border-color: #d97706; }
        .btn-warning:hover { background: #b45309; color: white; }
        .btn-danger { background: #dc3838; color: white; border-color: #dc3838; }
        .btn-danger:hover { background: #b91c1c; color: white; }
        .btn-success { background: #16a34a; color: white; border-color: #16a34a; }
        .btn-success:hover { background: #15803d; color: white; }
        .btn-info { background: #2563eb; color: white; border-color: #2563eb; }
        .btn-info:hover { background: #1d4ed8; color: white; }
        .btn-sm { padding: 4px 12px; font-size: 12px; }
        
        /* Forms */
        .form-label { font-size: 12px; font-weight: 500; color: #1e293b; margin-bottom: 4px; }
        .form-control, .form-select { font-family: 'Hanken Grotesk', sans-serif; font-size: 14px; padding: 10px 14px; border: 1px solid #e2e3e0; border-radius: 0.5rem; background: white; color: #1e293b; width: 100%; transition: border-color 0.2s; }
        .form-control:focus, .form-select:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,0.15); outline: none; }
        
        /* Filter Section */
        .filter-section { background: white; border: 1px solid #e2e3e0; border-radius: 1rem; padding: 16px 20px; margin-bottom: 20px; }
        .filter-section .filter-label { font-size: 12px; font-weight: 500; color: #1e293b; margin-bottom: 4px; }
        
        /* Alerts */
        .alert { border-radius: 0.5rem; border: 1px solid transparent; padding: 14px 18px; font-size: 12px; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #166534; }
        .alert-danger { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
        
        /* Utilities */
        .text-muted { color: #434655; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .gap-1 { gap: 0.25rem; } .gap-2 { gap: 0.5rem; } .gap-3 { gap: 1rem; }
        .mb-0 { margin-bottom: 0; } .mb-1 { margin-bottom: 0.25rem; } .mb-2 { margin-bottom: 0.5rem; } .mb-3 { margin-bottom: 1rem; } .mb-4 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.25rem; } .mt-2 { margin-top: 0.5rem; } .mt-3 { margin-top: 1rem; } .mt-4 { margin-top: 1.5rem; }
        .d-flex { display: flex; } .align-items-center { align-items: center; } .justify-content-between { justify-content: space-between; }
        
        /* Responsive */
        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1045; }
            .sidebar-overlay.active { display: block; }
            .sidebar-toggle { display: block; }
            .main-content { margin-left: 0; }
            .top-navbar { padding: 0 16px; }
            .page-content { padding: 16px; }
        }
        @media (min-width: 768px) { .sidebar-toggle { display: none; } }
        @media (min-width: 768px) and (max-width: 1024px) {
            .sidebar { width: 72px; }
            .sidebar-brand .brand-text, .sidebar-brand .brand-sub,
            .sidebar-nav .nav-text, .sidebar-nav .nav-label,
            .sidebar-footer .nav-text { display: none; }
            .sidebar-brand { padding: 16px 18px; justify-content: center; }
            .sidebar-nav .nav-item { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; }
        }
        
/* ============================================
   PAGINATION
   ============================================ */
.pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
}

.pagination .page-item {
    display: inline-block;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border: 1px solid var(--border-subtle, #e2e3e0);
    border-radius: 0.5rem;
    color: var(--text-primary, #1e293b);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    background: white;
    font-family: var(--font-family, 'Hanken Grotesk', sans-serif);
}

.pagination .page-link:hover {
    background: var(--surface-container-low, #f2f4f9);
    border-color: var(--primary, #1d4ed8);
    color: var(--primary, #1d4ed8);
}

.pagination .page-item.active .page-link {
    background: var(--primary, #1d4ed8);
    border-color: var(--primary, #1d4ed8);
    color: white;
    box-shadow: 0 2px 8px rgba(29, 78, 216, 0.25);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.4;
    pointer-events: none;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    padding: 0 16px;
}

/* Pagination info text */
.pagination-info {
    font-size: 13px;
    color: var(--on-surface-variant, #434655);
}

/* Responsive pagination */
@media (max-width: 576px) {
    .pagination .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 12px;
        padding: 0 8px;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0 10px;
    }
    .pagination-info {
        font-size: 12px;
    }
}
            </style>
    
    @stack('styles')
</head>
<body>
    <!-- ========================================== -->
    <!-- SIDEBAR -->
    <!-- ========================================== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ auth()->user()->role === 'Admin' ? route('admin.dashboard') : route('staff.dashboard') }}">
                <div style="width:55px; height:55px; display:flex; align-items:center; justify-content:center;">
                    <img src="{{ asset('images/logoindorisakti3.png') }}" alt="Logo" 
                        style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div>
                    <div class="brand-text">PT INDO RISAKTI</div>
                    <div class="brand-sub">Sistem Pelaporan Produksi</div>
                </div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ auth()->user()->role === 'Admin' ? route('admin.dashboard') : route('staff.dashboard') }}" 
               class="nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            
            @if(auth()->user()->role === 'Admin')
                <div class="nav-label">Master Data</div>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people nav-icon"></i><span class="nav-text">Data User</span>
                </a>
                <a href="{{ route('admin.produk.index') }}" class="nav-item {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                    <i class="bi bi-box nav-icon"></i><span class="nav-text">Data Produk</span>
                </a>
                <a href="{{ route('admin.supplier.index') }}" class="nav-item {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}">
                    <i class="bi bi-truck nav-icon"></i><span class="nav-text">Data Supplier</span>
                </a>
                
                <div class="nav-label">Transaksi</div>
                <a href="{{ route('admin.project.index') }}" class="nav-item {{ request()->routeIs('admin.project.*') ? 'active' : '' }}">
                    <i class="bi bi-folder nav-icon"></i><span class="nav-text">Project</span>
                </a>
                <a href="{{ route('admin.purchase-order.index') }}" class="nav-item {{ request()->routeIs('admin.purchase-order.*') ? 'active' : '' }}">
                    <i class="bi bi-cart nav-icon"></i><span class="nav-text">Purchase Order</span>
                </a>
                <a href="{{ route('admin.progress.index') }}" class="nav-item {{ request()->routeIs('admin.progress.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history nav-icon"></i><span class="nav-text">Progress Produksi</span>
                </a>
                
                <div class="nav-label">Laporan</div>
                <a href="{{ route('admin.laporan.produk') }}" class="nav-item {{ request()->routeIs('admin.laporan.produk') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text nav-icon"></i><span class="nav-text">Laporan Produk</span>
                </a>
                <a href="{{ route('admin.laporan.po') }}" class="nav-item {{ request()->routeIs('admin.laporan.po') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text nav-icon"></i><span class="nav-text">Laporan Purchase Order</span>
                </a>
                <a href="{{ route('admin.laporan.progress') }}" class="nav-item {{ request()->routeIs('admin.laporan.progress') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text nav-icon"></i><span class="nav-text">Laporan Progress</span>
                </a>
            @else
                <div class="nav-label">Transaksi</div>
                <a href="{{ route('staff.progress.index') }}" class="nav-item {{ request()->routeIs('staff.progress.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history nav-icon"></i><span class="nav-text">Input Progress Produksi</span>
                </a>
            @endif
        </nav>

        <!-- LOGOUT SIDEBAR -->
        <!-- <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
            </form>
        </div> -->
    </aside>
    
    <!-- ========================================== -->
    <!-- MAIN CONTENT -->
    <!-- ========================================== -->
    <div class="main-content">
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="navbar-right">
                <div class="dropdown">
                    <a href="#" class="user-menu" id="userDropdown" data-bs-toggle="dropdown">
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                        <div>
                            <div class="user-name">{{ auth()->user()->nama }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size:12px; color:#434655;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">
                            <strong>{{ auth()->user()->nama }}</strong><br>
                            <small class="text-muted">{{ auth()->user()->role }}</small>
                        </span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    // Sidebar Toggle (tetap)
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }
        
        if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
        
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // ==========================================
    // SELECT2 INITIALIZATION
    // ==========================================
    $(document).ready(function() {
        $('.select2').each(function() {
            var placeholder = $(this).data('placeholder') || 'Pilih...';
            var searchPlaceholder = $(this).data('search-placeholder') || 'Ketik untuk mencari...';
            
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: placeholder,
                allowClear: true,
                dropdownParent: $('body'),
                language: {
                    inputTooShort: function() {
                        return 'Ketik minimal 1 karakter';
                    },
                    noResults: function() {
                        return 'Tidak ada hasil ditemukan';
                    },
                    searching: function() {
                        return 'Mencari...';
                    }
                }
            });
            
            // Set placeholder di input pencarian Select2
            $(this).on('select2:open', function() {
                setTimeout(function() {
                    var searchField = document.querySelector('.select2-search__field');
                    if (searchField) {
                        searchField.placeholder = searchPlaceholder;
                    }
                }, 100);
            });
        });
    });
</script>
    @stack('scripts')
</body>
</html>