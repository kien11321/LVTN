@extends('layouts.app')

@section('title', 'Quản lý sinh viên')

@section('content')
    <h1 class="page-title">Quản lý sinh viên</h1>

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

    <!-- Action Buttons -->
    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <form method="GET" action="{{ route('sinhvien.index') }}" class="search-bar" style="flex: 1; min-width: 300px;">
            <input type="text" class="search-input" name="search" placeholder="Tìm kiếm theo tên hoặc MSSV..."
                value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
        </form>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form method="POST" action="{{ route('sinhvien.import.post') }}" enctype="multipart/form-data"
                style="display: flex; align-items: center; gap: 10px;">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" required id="import-file" style="display: none;"
                    onchange="this.form.submit()">
                <label for="import-file" class="btn btn-primary" style="cursor: pointer; margin: 0;">
                    📥 Import Excel
                </label>
            </form>
            <a href="{{ route('sinhvien.create') }}" class="btn btn-success">➕ Thêm mới</a>
        </div>
    </div>

    <!-- Student Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Ngành</th>
                    <th>Email</th>
                    @if (auth()->user()->vaitro === 'giangvien')
                        <th>Nhóm</th>
                    @endif

                    @if (auth()->user()->vaitro === 'admin')
                        <!-- Only show this column for admin -->
                        <th>GVHD</th>
                    @endif
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sinhViens as $index => $sv)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sv->mssv }}</td>
                        <td>{{ $sv->hoten }}</td>
                        <td>{{ $sv->lop }}</td>
                        <td>{{ $sv->khoa }}</td>
                        <td>{{ $sv->email }}</td>

                        @if (auth()->user()->vaitro === 'giangvien')
                            <td>{{ $sv->nhom }}</td>
                        @endif

                        @if (auth()->user()->vaitro === 'admin')
                            <!-- Only show this column for admin -->
                            <td>{{ $sv->gvhd }}</td>
                        @endif

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('sinhvien.edit', $sv->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form method="POST" action="{{ route('sinhvien.destroy', $sv->id) }}"
                                    style="display: inline;"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa sinh viên này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                            Không có sinh viên nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
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
    </style>
@endsection
