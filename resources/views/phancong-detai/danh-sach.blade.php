@extends('layouts.app')

@section('title', 'Danh sách đề tài')

@section('content')
    <div class="page-detai-list">

        <div class="page-header">
            <h1 class="page-title">Danh sách đề tài</h1>
            <div class="page-actions">
                <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">← Quay lại</a>
                <a href="{{ route('phancong-detai.create-detai') }}" class="btn btn-primary">➕ Tạo đề tài mới</a>
            </div>
        </div>

        @if (session('success'))
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
                                <span class="badge {{ $deTai->loai === 'nhom' ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $deTai->loai === 'nhom' ? 'Nhóm' : 'Cá nhân' }}
                                </span>
                            </td>
                            <td>
                                @if ($deTai->nhomSinhVien)
                                    <strong>{{ $deTai->nhomSinhVien->ten_nhom }}</strong>
                                @else
                                    <span class="text-muted">Chưa phân</span>
                                @endif
                            </td>
                            <td>
                                @if ($deTai->nhom_sinhvien_id)
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

    </div>
@endsection
