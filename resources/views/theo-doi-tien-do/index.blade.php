@extends('layouts.app')

@section('title', 'Theo dõi Tiến độ & Đánh giá Giữa kỳ')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Theo dõi Tiến độ & Đánh giá Giữa kỳ</h1>
        <p class="page-subtitle">Danh sách sinh viên thuộc nhóm hướng dẫn và cập nhật tiến độ thực hiện.</p>
    </div>

    <div class="page-actions">
        <a href="{{ route('theo-doi-tien-do.export-excel') }}" class="btn btn-success">
            📊 Xuất Excel
        </a>
    </div>
</div>

@php
    $isAdmin = auth()->user()->vaitro === 'admin';
@endphp

@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>MSSV</th>
                <th>Họ và Tên</th>
                <th>Lớp</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Tên Đề tài</th>
                @if ($isAdmin)
                    <th>GVHD</th>
                @endif
                <th>Nhóm</th>
                <th>Tiến độ</th>
                <th>Quyết định</th>
                <th>Ghi chú</th>
                @if (!$isAdmin)
                    <th>Hành động</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse($nhomGroups as $nhomId => $sinhViens)
                @php
                    $firstSV = $sinhViens->first();
                @endphp

                @foreach ($sinhViens as $index => $sv)
                    <tr>
                        <td class="sv-info">{{ $sv->mssv }}</td>
                        <td class="sv-info">{{ $sv->hoten }}</td>
                        <td class="sv-info">{{ $sv->lop }}</td>
                        <td class="sv-info">{{ $sv->email }}</td>
                        <td class="sv-info">{{ $sv->sdt }}</td>

                        @if ($index === 0)
                            <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                {{ $firstSV->ten_detai ?? '-' }}
                            </td>

                            @if ($isAdmin)
                                <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                    {{ $firstSV->gvhd ?? '-' }}
                                </td>
                            @endif

                            <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                {{ $firstSV->ten_nhom }}
                            </td>

                            <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                <span class="badge badge-info">{{ $firstSV->tien_do ?? '-' }}%</span>
                            </td>

                            <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                <span class="badge badge-status {{ $firstSV->quyet_dinh }}">
                                    {{ $firstSV->quyet_dinh }}
                                </span>
                            </td>

                            <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                {{ $firstSV->ghi_chu ?? '-' }}
                            </td>

                            @if (!$isAdmin)
                                <td rowspan="{{ $sinhViens->count() }}" class="group-cell">
                                    <button class="btn btn-primary btn-sm"
                                        onclick="openUpdateModal({{ $nhomId }}, '{{ $firstSV->ten_nhom }}', {{ $firstSV->tien_do ?? 0 }}, '{{ $firstSV->quyet_dinh }}', '{{ $firstSV->ghi_chu }}')">
                                        ✏️ Đánh giá
                                    </button>
                                </td>
                            @endif
                        @endif
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="11" class="text-center">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL --}}
<div id="updateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cập nhật tiến độ</h3>
            <span class="close" onclick="closeUpdateModal()">×</span>
        </div>

        <form method="POST" id="updateForm">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <div class="form-group">
                    <label>Nhóm</label>
                    <div id="nhom-info" class="readonly-box"></div>
                </div>

                <div class="form-group">
                    <label>Tiến độ (%)</label>
                    <input type="number" id="tien_do" name="tien_do" class="form-control" min="0" max="100">
                </div>

                <div class="form-group">
                    <label>Quyết định</label>
                    <select id="quyet_dinh" name="quyet_dinh" class="form-control">
                        <option value="duoc_lam_tiep">Được làm tiếp</option>
                        <option value="tam_dung">Tạm dừng</option>
                        <option value="huy">Hủy</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea id="ghi_chu" name="ghi_chu" class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeUpdateModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection
