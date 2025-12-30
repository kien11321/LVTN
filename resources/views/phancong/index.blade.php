@extends('layouts.app')

@section('title', 'Phân công hướng dẫn')

@section('content')
    <h1 class="page-title">Phân công hướng dẫn</h1>

    @if (session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    {{-- Tìm kiếm sinh viên --}}
    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <form method="GET" action="{{ route('phancong.index') }}" class="search-bar"
            style="flex: 1; min-width: 300px; display: flex; gap: 5px;">
            <input type="text" class="search-input" name="search" placeholder="Tìm kiếm theo tên hoặc MSSV..."
                value="{{ request('search') }}"
                style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; flex-grow: 1;">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
            @if (request('search'))
                <a href="{{ route('phancong.index') }}" class="btn btn-secondary"
                    style="text-decoration: none; padding: 8px; background: #eee; color: #333; border-radius: 4px;">Xóa
                    lọc</a>
            @endif
        </form>
    </div>

    <!-- Assignment Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Tên</th>
                    <th>Nhóm</th>
                    <th>Giảng viên hướng dẫn</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($phanCongs) && $phanCongs->count() > 0)
                    @foreach ($phanCongs as $pc)
                        <tr id="sinhvien-{{ $pc->sinhvien_id }}">
                            <td>{{ $pc->mssv }}</td>
                            <td>{{ $pc->hoten }}</td>
                            <td>
                                <form method="POST" action="{{ route('phancong.update-nhom') }}" class="assignment-form"
                                    style="display: inline;" id="nhomForm{{ $pc->sinhvien_id }}">
                                    @csrf
                                    <input type="hidden" name="sinhvien_id" value="{{ $pc->sinhvien_id }}">
                                    <select name="nhom_id" class="nhom-select"
                                        onchange="handleNhomChange(this, {{ $pc->sinhvien_id }})">
                                        <option value="">-- Chọn nhóm --</option>
                                        @php
                                            $nhomSinhViens = \App\Models\NhomSinhVien::orderBy('ten_nhom')->get();
                                            $currentNhomId = $pc->nhom_id ?? null;
                                        @endphp
                                        @foreach ($nhomSinhViens as $nhom)
                                            <option value="{{ $nhom->id }}"
                                                {{ $currentNhomId == $nhom->id ? 'selected' : '' }}>
                                                {{ $nhom->ten_nhom }}
                                            </option>
                                        @endforeach
                                        <option value="new">➕ Tạo nhóm mới</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('phancong.update') }}" class="assignment-form"
                                    style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="sinhvien_id" value="{{ $pc->sinhvien_id }}">
                                    <select name="giangvien_id" class="gv-select" onchange="this.form.submit()">
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach ($giangViens as $gv)
                                            <option value="{{ $gv->id }}"
                                                {{ $pc->giangvien_id == $gv->id ? 'selected' : '' }}>
                                                {{ $gv->hoten }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span
                                    class="status-badge {{ $pc->trang_thai == 'Đã phân công' ? 'status-assigned' : 'status-pending' }}">
                                    {{ $pc->trang_thai }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                            Không có dữ liệu sinh viên
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <style>
        .gv-select,
        .nhom-select {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .gv-select:focus,
        .nhom-select:focus {
            outline: none;
            border-color: #007bff;
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

        .assignment-form {
            width: 100%;
        }

        /* Style cho phần Sinh viên chưa đăng ký */
        .chuadangky-section {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .chuadangky-section h2 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .chuadangky-section p {
            color: #856404;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .chuadangky-section .table-container {
            background: white;
            border-radius: 4px;
            overflow: hidden;
        }

        .chuadangky-section table {
            margin: 0;
        }

        .chuadangky-section table thead {
            background: #ffc107;
            color: #856404;
        }

        .chuadangky-section table tbody tr:hover {
            background: #fffbf0;
        }
    </style>

    <script>
        function handleNhomChange(select, sinhvienId) {
            if (select.value === 'new') {
                if (confirm('Bạn có muốn tạo nhóm mới và thêm sinh viên vào nhóm đó không?')) {
                    document.getElementById('nhomForm' + sinhvienId).submit();
                } else {
                    select.value = ''; // Reset về trạng thái ban đầu
                }
            } else if (select.value !== '') {
                document.getElementById('nhomForm' + sinhvienId).submit();
            }
        }
    </script>
@endsection
