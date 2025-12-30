@extends('layouts.app')

@section('title', 'Danh sách đề tài')

@section('content')
<div class="page-header">
    <h1 class="page-title">Danh sách đề tài</h1>
    <div class="page-actions">
        <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">← Quay lại</a>
        <a href="{{ route('phancong-detai.create-detai') }}" class="btn btn-primary">➕ Tạo đề tài mới</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên đề tài</th>
                <th>Mô tả</th>
                <th>Giảng viên HD</th>
                <th>Loại</th>
                <th>Nhóm được phân</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deTais as $index => $deTai)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $deTai->ten_detai }}</strong></td>
                <td>{{ Str::limit($deTai->mo_ta, 100) }}</td>
                <td>{{ $deTai->giangVien->hoten ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $deTai->loai === 'nhom' ? 'primary' : 'secondary' }}">
                        {{ $deTai->loai === 'nhom' ? 'Nhóm' : 'Cá nhân' }}
                    </span>
                </td>
                <td>
                    @if($deTai->nhomSinhVien)
                        <strong>{{ $deTai->nhomSinhVien->ten_nhom }}</strong>
                    @else
                        <span class="text-muted">Chưa phân</span>
                    @endif
                </td>
                <td>
                    @if($deTai->nhom_sinhvien_id)
                        <span class="status-badge status-assigned">Đã phân công</span>
                    @else
                        <span class="status-badge status-available">Còn trống</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Chưa có đề tài nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-actions {
        display: flex;
        gap: 10px;
    }

    .alert {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 500;
    }

    .badge-primary {
        background: #007bff;
        color: white;
    }

    .badge-secondary {
        background: #6c757d;
        color: white;
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

    .status-available {
        background: #cfe2ff;
        color: #084298;
    }

    .text-muted {
        color: #6c757d;
    }

    .text-center {
        text-align: center;
        padding: 20px;
        color: #666;
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
</style>
@endsection









