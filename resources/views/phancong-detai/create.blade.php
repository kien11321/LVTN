@extends('layouts.app')

@section('title', 'Phân công đề tài')

@section('content')
<div class="page-header">
    <h1 class="page-title">Phân công đề tài cho {{ $nhom->ten_nhom }}</h1>
    <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">← Quay lại</a>
</div>

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Thông tin nhóm</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Tên nhóm:</label>
                    <p><strong>{{ $nhom->ten_nhom }}</strong></p>
                </div>
                <div class="info-group">
                    <label>Trưởng nhóm:</label>
                    <p>{{ $nhom->truongNhom->hoten ?? '-' }}</p>
                </div>
                <div class="info-group">
                    <label>Thành viên:</label>
                    <ul class="member-list">
                        @foreach($nhom->sinhViens as $sv)
                            <li>{{ $sv->hoten }} ({{ $sv->mssv }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Chọn đề tài</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('phancong-detai.store') }}" id="assignForm">
                    @csrf
                    <input type="hidden" name="nhom_sinhvien_id" value="{{ $nhom->id }}">
                    <input type="hidden" name="detai_id" id="selected_detai_id">

                    <div class="form-group">
                        <label for="detai_select">Chọn đề tài: <span class="required">*</span></label>
                        <select 
                            id="detai_select" 
                            class="form-control"
                            onchange="selectFromDropdown(this)"
                        >
                            <option value="">-- Chọn đề tài --</option>
                            @foreach($deTais as $dt)
                                <option 
                                    value="{{ $dt->id }}"
                                    data-ten="{{ $dt->ten_detai }}"
                                    data-mota="{{ $dt->mo_ta }}"
                                    data-giangvien="{{ $dt->giangVien ? $dt->giangVien->hoten : 'Chưa có' }}"
                                    data-loai="{{ $dt->loai }}"
                                >
                                    {{ $dt->ten_detai }} - GV: {{ $dt->giangVien ? $dt->giangVien->hoten : 'Chưa có' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($deTais->isEmpty())
                        <div class="alert alert-warning">
                            ⚠️ Không có đề tài nào còn trống. <a href="{{ route('phancong-detai.create-detai') }}">Tạo đề tài mới</a>
                        </div>
                    @endif

                    <div id="selected_detai_display" class="selected-detai" style="display: none;">
                        <h4>Đề tài đã chọn:</h4>
                        <div class="detai-card">
                            <div class="detai-title" id="display_ten_detai"></div>
                            <div class="detai-info">
                                <span id="display_giangvien"></span> • 
                                <span id="display_loai"></span>
                            </div>
                            <div class="detai-desc" id="display_mo_ta"></div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                                ✕ Chọn đề tài khác
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            Phân công đề tài
                        </button>
                        <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .col-md-4 {
        flex: 0 0 33.333%;
    }

    .col-md-8 {
        flex: 0 0 66.666%;
    }

    .card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
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

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        font-weight: 600;
        color: #666;
        margin-bottom: 5px;
        display: block;
    }

    .info-group p {
        margin: 0;
    }

    .member-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .member-list li {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .member-list li:last-child {
        border-bottom: none;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
    }

    .required {
        color: #dc3545;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        padding: 12px 15px;
        border-radius: 4px;
        border: 1px solid #ffeaa7;
        margin-bottom: 20px;
    }

    .alert-warning a {
        color: #004085;
        font-weight: 600;
        text-decoration: underline;
    }

    .selected-detai {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .detai-card {
        background: white;
        padding: 15px;
        border-radius: 4px;
        border: 2px solid #007bff;
    }

    .detai-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .detai-info {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .detai-desc {
        font-size: 14px;
        color: #333;
        margin-bottom: 10px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
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

    .no-results {
        padding: 12px;
        text-align: center;
        color: #666;
    }
</style>

<script>
const selectedDisplay = document.getElementById('selected_detai_display');
const submitBtn = document.getElementById('submitBtn');

// Chọn đề tài từ dropdown
function selectFromDropdown(select) {
    const option = select.options[select.selectedIndex];
    if (!option.value) {
        clearSelection();
        return;
    }

    const id = option.value;
    const ten = option.dataset.ten;
    const moTa = option.dataset.mota;
    const giangvien = option.dataset.giangvien;
    const loai = option.dataset.loai;

    selectDeTai(id, ten, moTa, giangvien, loai);
}

// Hiển thị đề tài đã chọn
function selectDeTai(id, ten, moTa, giangvien, loai) {
    document.getElementById('selected_detai_id').value = id;
    document.getElementById('display_ten_detai').textContent = ten;
    document.getElementById('display_mo_ta').textContent = moTa || 'Không có mô tả';
    document.getElementById('display_giangvien').textContent = 'GV: ' + giangvien;
    document.getElementById('display_loai').textContent = loai === 'nhom' ? 'Nhóm' : 'Cá nhân';
    
    selectedDisplay.style.display = 'block';
    submitBtn.disabled = false;
}

// Xóa lựa chọn
function clearSelection() {
    document.getElementById('selected_detai_id').value = '';
    document.getElementById('detai_select').value = '';
    selectedDisplay.style.display = 'none';
    submitBtn.disabled = true;
}
</script>
@endsection

