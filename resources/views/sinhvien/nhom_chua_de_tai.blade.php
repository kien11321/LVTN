@extends('layouts.app')

@section('title', 'Sinh viên chưa có đề tài')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Sinh viên chưa có đề tài</h1>
            <p class="page-subtitle">- Danh sách thống kê sinh viên chưa đăng ký đề tài LVTN</p>
        </div>
    </div>

    <div class="search-container">
        <form method="GET" action="{{ route('sinhvien.nhom-chua-de-tai') }}" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Tìm kiếm MSSV hoặc tên sinh viên..."
                value="{{ $search }}">
            <button type="submit" class="btn-search">Tìm kiếm</button>
            @if ($search)
                <a href="{{ route('sinhvien.nhom-chua-de-tai') }}" class="btn-reset">Hủy lọc</a>
            @endif
        </form>
    </div>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th style="width: 150px;">Mã SV</th>
                        <th>Họ tên</th>
                        <th style="width: 120px;">Lớp</th>
                        <th style="width: 150px;">Ngành</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sinhViens as $index => $sv)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="mssv-column">{{ $sv->mssv }}</td>
                            <td class="name-column">{{ $sv->hoten }}</td>
                            <td>{{ $sv->lop }}</td>
                            <td>{{ $sv->khoa }}</td> {{-- Đây là cột Ngành lấy từ trường khoa --}}
                            <td>{{ $sv->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                Không có sinh viên nào chưa có đề tài.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')

    <style>
        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 24px;
            color: #1a202c;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        .search-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn-search {
            background: #1a202c;
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-reset {
            background: #edf2f7;
            color: #4a5568;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .custom-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            background: #f7fafc;
            padding: 15px;
            text-align: left;
            color: #4a5568;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #edf2f7;
        }

        .custom-table td {
            padding: 15px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
            color: #2d3748;
            font-size: 14px;
        }

        .mssv-column {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 600;
            color: #1a202c;
        }

        .name-column {
            font-weight: 500;
            color: #1a202c;
        }

        .empty-state {
            text-align: center;
            padding: 60px !important;
            color: #a0aec0;
            font-style: italic;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
    </style>
@endpush
