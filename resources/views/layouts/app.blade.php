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


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo-link">
                <img src="{{ asset('Logo_STU.png') }}" alt="STU Logo" class="logo">
            </a>
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
                                <a href="{{ $childUrl }}"
                                    class="submenu-item {{ $isChildActive ? 'active' : '' }}">
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
        const fileInput = document.getElementById('fileInput');
        const fileNameEl = document.getElementById('fileName');

        if (fileInput && fileNameEl) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Không tệp nào được chọn';
                fileNameEl.textContent = fileName;
            });
        }
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
