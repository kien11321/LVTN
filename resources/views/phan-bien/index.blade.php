@extends('layouts.app')

@section('title', 'Phân công Giảng viên Phản biện')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Phân công Giảng viên Phản biện</h1>
        <p class="page-subtitle">Giảng viên phản biện không được trùng với giảng viên hướng dẫn.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('phan-bien.export') }}" class="btn btn-success">⬇️ Export ra Excel</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nhóm</th>
                    <th>Tên Đề tài</th>
                    <th>Thành viên Nhóm</th>
                    <th>Giảng viên Hướng dẫn</th>
                    <th>Giảng viên Phản biện</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nhoms as $nhom)
                    @php
                        $deTai = $nhom->deTai;
                        $gvhd = $deTai?->giangVien?->hoten;
                        $gvpb = $deTai?->giangVienPhanBien?->hoten;
                    @endphp
                    <tr>
                        <td><strong>{{ $nhom->ten_nhom }}</strong></td>
                        <td>
                            @if($deTai)
                                <div>{{ $deTai->ten_detai }}</div>
                            @else
                                <span class="text-muted">Chưa có đề tài</span>
                            @endif
                        </td>
                        <td>
                            @if($nhom->sinhViens->count())
                                <ul class="member-list-inline">
                                    @foreach($nhom->sinhViens as $sv)
                                        <li>{{ $sv->hoten }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Chưa có thành viên</span>
                            @endif
                        </td>
                        <td>{{ $gvhd ?? 'Chưa có' }}</td>
                        <td>
                            @if($deTai)
                            <form method="POST" action="{{ route('phan-bien.update') }}" class="inline-form" id="form_{{ $deTai->id }}">
                                @csrf
                                <input type="hidden" name="detai_id" value="{{ $deTai->id }}">
                                <select name="gvpb_id" id="gvpb_{{ $deTai->id }}" class="form-control-inline" required>
                                    <option value="">-- Chọn giảng viên --</option>
                                    @foreach($giangViens as $gv)
                                        @if($gv->id != $deTai->giangvien_id)
                                            <option value="{{ $gv->id }}" {{ $gvpb && $gv->id == $deTai->giangvien_phanbien_id ? 'selected' : '' }}>
                                                {{ $gv->hoten }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 8px;" id="btn_{{ $deTai->id }}" onclick="console.log('Button clicked for detai {{ $deTai->id }}');">Phân công PB</button>
                            </form>
                            @else
                                <span class="text-muted">Cần phân công đề tài trước</span>
                            @endif
                        </td>
                        <td class="text-muted">—</td>
                        <td>
                            @if($gvpb)
                                <span class="status-assigned">Đã phân công</span>
                            @else
                                <span class="status-pending">Chưa phân công</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Chưa có nhóm nào</td>
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
    .page-subtitle {
        margin: 6px 0 0;
        color: #666;
    }
    .page-actions {
        display: flex;
        align-items: center;
    }
    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead th {
        background: #f3f4f6;
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
    }
    tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    .text-muted {
        color: #6b7280;
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
    .btn {
        display: inline-block;
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        color: #fff;
        background: #4f46e5;
    }
    .btn-success {
        background: #16a34a;
    }
    .btn-primary {
        background: #4f46e5;
    }
    .btn-sm {
        padding: 6px 10px;
        font-size: 13px;
    }
    .form-control-inline {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
    }
    .inline-form {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .status-assigned {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        background: #d1fae5;
        color: #15803d;
        font-weight: 600;
    }
    .status-pending {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        background: #ede9fe;
        color: #6d28d9;
        font-weight: 600;
    }
    .alert {
        padding: 12px 14px;
        border-radius: 6px;
        margin-bottom: 12px;
    }
    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
    }
    .alert-success {
        background: #dcfce7;
        color: #166534;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.inline-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const select = form.querySelector('select[name="gvpb_id"]');
            const button = form.querySelector('button[type="submit"]');
            
            if (!select.value) {
                e.preventDefault();
                alert('Vui lòng chọn giảng viên phản biện!');
                return false;
            }
            
            // Disable button và hiển thị loading
            if (button) {
                button.disabled = true;
                button.textContent = 'Đang lưu...';
            }
            
            // Log để debug
            console.log('Submitting form:', {
                detai_id: form.querySelector('input[name="detai_id"]').value,
                gvpb_id: select.value,
                action: form.action
            });
            
            // Cho phép form submit bình thường
            return true;
        });
    });
});
</script>
@endsection




