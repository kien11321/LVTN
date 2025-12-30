@extends('layouts.app')

@section('title', 'Phân công đề tài cho nhóm')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Phân công đề tài cho nhóm</h1>
    </div>

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form phân công đề tài -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>Phân công / Sửa đề tài</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('phancong-detai.store-simple') }}">
                @csrf
                <div class="form-row-inline">
                    <div class="form-group-inline">
                        <label for="nhom_id">Chọn nhóm:</label>
                        <select id="nhom_id" name="nhom_id" class="form-control-inline" required
                            onchange="showCurrentDetai(this)">
                            <option value="">-- Chọn nhóm --</option>
                            @forelse($nhomSinhViens as $nhom)
                                <option value="{{ $nhom->id }}"
                                    data-current-detai="{{ $nhomDeTaiMap[$nhom->id] ?? '' }}">
                                    {{ $nhom->ten_nhom }} ({{ $nhom->sinhViens->count() }} thành viên)
                                    @if (isset($nhomDeTaiMap[$nhom->id]))
                                        {{-- - Đã có đề tài --}}
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>Không có nhóm nào</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group-inline flex-grow">
                        <label for="ten_detai">Tên đề tài:</label>
                        <input type="text" id="ten_detai" name="ten_detai" class="form-control-inline"
                            placeholder="Nhập tên đề tài..." required onchange="updatePlaceholder(this)">
                        <small id="current-detai-hint" class="text-muted"
                            style="display: none; font-size: 12px; margin-top: 3px;"></small>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách đề tài đã phân công (hiển thị luôn) -->
    <div id="danhSachDeTai" class="danh-sach-container">

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên nhóm</th>
                        <th>Đề tài</th>
                        <th>Thành viên</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allNhomSinhViens as $index => $nhom)
                        @php
                            $tenDetai = $nhom->deTai->ten_detai ?? 'Chưa có đề tài';
                            $gvName = $nhom->deTai && $nhom->deTai->giangVien ? $nhom->deTai->giangVien->hoten : null;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $nhom->ten_nhom }}</strong></td>
                            <td>
                                <strong>{{ $tenDetai }}</strong>
                                @if ($gvName)
                                    <br><small class="text-muted">GV: {{ $gvName }}</small>
                                @endif
                            </td>
                            <td>
                                <ul class="member-list-inline">
                                    @forelse($nhom->sinhViens as $sv)
                                        <li>{{ $sv->hoten }}</li>
                                    @empty
                                        <li class="text-muted">Chưa có thành viên</li>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Chưa có nhóm nào được phân công</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .detai-info {
            max-width: 300px;
        }

        .text-muted {
            color: #6c757d;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-assigned {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 4px 12px;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            background: #f8f9fa;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .card-body {
            padding: 20px;
        }

        .form-row-inline {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .form-group-inline {
            display: flex;
            flex-direction: column;
        }

        .form-group-inline.flex-grow {
            flex: 1;
        }

        .form-group-inline label {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-control-inline {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control-inline:focus {
            outline: none;
            border-color: #007bff;
        }

        .danh-sach-container {
            margin-top: 20px;
        }

        .member-list-inline {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .member-list-inline li {
            display: inline;
        }

        .member-list-inline li:not(:last-child):after {
            content: ", ";
        }
    </style>

    <script>
        function showCurrentDetai(select) {
            const selectedValue = select.value;
            const hint = document.getElementById('current-detai-hint');
            const input = document.getElementById('ten_detai');

            // Nếu chưa chọn nhóm, reset form
            if (!selectedValue) {
                hint.style.display = 'none';
                input.value = '';
                input.placeholder = 'Nhập tên đề tài...';
                return;
            }

            // Lấy đề tài hiện tại từ option được chọn
            const option = select.options[select.selectedIndex];
            const currentDetai = option.getAttribute('data-current-detai');

            if (currentDetai && currentDetai.trim() !== '') {
                // Nhóm đã có đề tài - hiển thị để sửa
                hint.textContent = 'Đề tài hiện tại: ' + currentDetai + ' (Bạn có thể sửa lại)';
                hint.style.display = 'block';
                input.value = currentDetai;
                input.placeholder = 'Sửa tên đề tài...';
            } else {
                // Nhóm chưa có đề tài - để trống để nhập mới
                hint.style.display = 'none';
                input.value = '';
                input.placeholder = 'Nhập tên đề tài...';
            }
        }

        function updatePlaceholder(input) {
            // Placeholder sẽ tự động cập nhật khi user nhập
        }

        // Kiểm tra form submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action*="store-simple"]');
            console.log('Form found:', form);

            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...');
                    const nhomId = document.getElementById('nhom_id').value;
                    const tenDetai = document.getElementById('ten_detai').value.trim();

                    console.log('nhom_id:', nhomId);
                    console.log('ten_detai:', tenDetai);

                    if (!nhomId) {
                        e.preventDefault();
                        alert('Vui lòng chọn nhóm!');
                        return false;
                    }

                    if (!tenDetai) {
                        e.preventDefault();
                        alert('Vui lòng nhập tên đề tài!');
                        return false;
                    }

                    // Hiển thị loading
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Đang lưu...';
                    }

                    console.log('Form submitted successfully');
                });
            } else {
                console.error('Form not found!');
            }
        });
    </script>
@endsection
