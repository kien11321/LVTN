<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý sinh viên') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            padding: 20px;
            background: #1a252f;
            border-bottom: 1px solid #34495e;
        }

        .sidebar-header .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar-menu a:hover {
            background: #34495e;
        }

        .sidebar-menu a.active {
            background: #3498db;
            color: white;
        }

        /* Menu với submenu */
        .menu-item {
            position: relative;
        }

        .menu-item-header {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1;
            cursor: pointer;
            transition: background 0.3s;
            user-select: none;
        }

        .menu-item-header:hover {
            background: #34495e;
        }

        .menu-item.has-children.active>.menu-item-header {
            background: #3498db;
            color: white;
        }

        .menu-icon {
            margin-right: 10px;
            font-size: 16px;
        }

        .menu-title {
            flex: 1;
        }

        .menu-arrow {
            font-size: 10px;
            transition: transform 0.3s;
        }

        .menu-item.has-children.active .menu-arrow {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: #1a252f;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu-item {
            display: block;
            padding: 10px 20px 10px 50px;
            color: #bdc3c7;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
            font-size: 14px;
        }

        .submenu-item:hover {
            background: #34495e;
            color: white;
        }

        .submenu-item.active {
            background: #3498db;
            color: white;
        }

        .menu-item>a {
            display: flex;
            align-items: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .btn-file {
            padding: 8px 16px;
            background: #e0e0e0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .file-name {
            margin-left: 10px;
            color: #666;
            font-size: 14px;
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: #333;
            font-size: 14px;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #2c3e50;
            color: white;
        }

        thead th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        tbody td {
            padding: 12px 15px;
            font-size: 14px;
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }
        .logo {
            width: 70%;
            height: 50%;
            margin: 0 auto;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('Logo_STU.png') }}" alt="STU Logo" class="logo">
        </div>
        <nav class="sidebar-menu">
            @php
                $userRole = auth()->user()->vaitro ?? 'guest';
                $menus = \App\Helpers\MenuHelper::getMenuByRole($userRole);
            @endphp

            @php
                // Kiểm tra xem có menu chính nào đang active không (không có submenu)
                $activeMainMenu = null;
                foreach ($menus as $menu) {
                    if (!isset($menu['children']) && isset($menu['route'])) {
                        if (\App\Helpers\MenuHelper::isActive($menu['route'])) {
                            $activeMainMenu = $menu['route'];
                            break;
                        }
                    }
                }
            @endphp

            @foreach ($menus as $menu)
                @if (isset($menu['children']) && !empty($menu['children']))
                    {{-- Menu có submenu --}}
                    @php
                        $hasActiveChild = \App\Helpers\MenuHelper::hasActiveChild($menu['children'], $activeMainMenu);
                        $menuId = 'menu-' . str_replace(' ', '-', strtolower($menu['title']));
                    @endphp
                    <div class="menu-item has-children {{ $hasActiveChild ? 'active' : '' }}">
                        <div class="menu-item-header" onclick="toggleSubmenu('{{ $menuId }}')">
                            <span class="menu-icon">{{ $menu['icon'] ?? '📁' }}</span>
                            <span class="menu-title">{{ $menu['title'] }}</span>
                            <span class="menu-arrow">▼</span>
                        </div>
                        <div class="submenu {{ $hasActiveChild ? 'show' : '' }}" id="{{ $menuId }}">
                            @foreach ($menu['children'] as $child)
                                @php
                                    $childParams = $child['params'] ?? [];
                                    $childUrl = route($child['route'], $childParams);
                                    // Chỉ active submenu item nếu không có menu chính nào cùng route đang active
                                    $isChildActive =
                                        !$activeMainMenu || $activeMainMenu !== $child['route']
                                            ? \App\Helpers\MenuHelper::isActive($child['route'], $childParams)
                                            : false;
                                @endphp
                                <a href="{{ $childUrl }}" class="submenu-item {{ $isChildActive ? 'active' : '' }}">
                                    {{ $child['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Menu đơn giản không có submenu --}}
                    <a href="{{ route($menu['route'] ?? 'dashboard') }}"
                        class="menu-item {{ \App\Helpers\MenuHelper::isActive($menu['route'] ?? 'dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">{{ $menu['icon'] ?? '📁' }}</span>
                        <span class="menu-title">{{ $menu['title'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                @if (request()->routeIs('sinhvien.*') || request()->routeIs('dashboard'))
                    {{-- <div class="file-input-wrapper">
                        <input type="file" id="fileInput" accept=".xlsx,.xls" style="display: none;">
                        <button type="button" class="btn-file" onclick="document.getElementById('fileInput').click()">Chọn tệp</button>
                        <span class="file-name" id="fileName">Không tệp nào được chọn</span>
                    </div>
                    <button class="btn btn-success">Import từ Excel</button>
                    <a href="{{ route('sinhvien.create') }}" class="btn btn-primary" style="text-decoration: none;">+ Thêm sinh viên</a> --}}
                @endif
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span>Xin chào, <strong>{{ Auth::user()->hoten ?? (Auth::user()->name ?? 'User') }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        // File input handler
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Không tệp nào được chọn';
            document.getElementById('fileName').textContent = fileName;
        });
    </script>

    @stack('scripts')

    <script>
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId);
            const menuItem = submenu.closest('.menu-item');

            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                menuItem.classList.remove('active');
            } else {
                // Đóng tất cả submenu khác
                document.querySelectorAll('.submenu.show').forEach(item => {
                    if (item.id !== menuId) {
                        item.classList.remove('show');
                        item.closest('.menu-item').classList.remove('active');
                    }
                });

                submenu.classList.add('show');
                menuItem.classList.add('active');
            }
        }

        // Mở submenu nếu có child active khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.submenu-item.active').forEach(activeItem => {
                const submenu = activeItem.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('show');
                    submenu.closest('.menu-item').classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
