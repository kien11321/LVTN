@extends('layouts.app')

@section('title', 'Quản lý sinh viên')

@section('content')
    <h1 class="page-title">Quản lý sinh viên</h1>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('dashboard') }}" class="search-bar">
        <input type="text" class="search-input" name="search" placeholder="Tìm kiếm theo tên hoặc M..." value="{{ $search ?? '' }}" id="searchInput">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
    </form>

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
                    <th>Nhóm</th>
                    <th>GVHD</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                @forelse($sinhViens as $index => $sv)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sv->mssv }}</td>
                    <td>{{ $sv->hoten }}</td>
                    <td>{{ $sv->lop }}</td>
                    <td>{{ $sv->khoa }}</td>
                    <td>{{ $sv->email }}</td>
                    <td>{{ $sv->nhom ?: '-' }}</td>
                    <td>{{ $sv->gvhd ?: '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm">Sửa</button>
                            <button class="btn btn-danger btn-sm">Xóa</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                        Không tìm thấy sinh viên nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
