@extends('layouts.app')

@section('title', 'Tạo Phiếu Giao Đề Tài')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tạo Phiếu Giao Đề Tài</h1>
        <p class="page-subtitle">Nhập thông tin bên dưới để xuất ra file Word theo template.</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tao-phieu-giao-detai.export-word') }}" id="phieuForm">
            @csrf

            <!-- Chọn nhóm để tự động điền (tùy chọn) -->
            <div class="form-group">
                <label for="nhom_select">Chọn nhóm để tự động điền thông tin (tùy chọn):</label>
                <select id="nhom_select" class="form-control" onchange="loadNhomData(this.value)">
                    <option value="">-- Chọn nhóm (hoặc nhập thủ công) --</option>
                    @if(isset($nhoms) && $nhoms->count() > 0)
                        @foreach($nhoms as $nhom)
                            <option value="{{ $nhom->id }}" 
                                data-sv1="{{ ($nhom->sinhViens && $nhom->sinhViens->first()) ? $nhom->sinhViens->first()->id : '' }}"
                                data-sv2="{{ ($nhom->sinhViens && $nhom->sinhViens->count() > 1) ? $nhom->sinhViens->skip(1)->first()->id : '' }}"
                                data-detai="{{ $nhom->deTai ? $nhom->deTai->id : '' }}"
                                data-gv="{{ ($nhom->deTai && $nhom->deTai->giangVien) ? $nhom->deTai->giangVien->id : '' }}">
                                {{ $nhom->ten_nhom }} - {{ ($nhom->deTai && $nhom->deTai->ten_detai) ? $nhom->deTai->ten_detai : 'Chưa có đề tài' }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Không có nhóm nào có đề tài</option>
                    @endif
                </select>
            </div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid #e5e7eb;">

            <!-- Sinh viên 1 -->
            <h3 style="margin-bottom: 16px; color: #1f2937;">Sinh viên 1</h3>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="sv1_hoten">Họ tên Sinh viên 1 <span class="required">*</span></label>
                    <input type="text" id="sv1_hoten" name="sv1_hoten" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="sv1_mssv">MSSV 1 <span class="required">*</span></label>
                    <input type="text" id="sv1_mssv" name="sv1_mssv" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="sv1_lop">Lớp 1</label>
                    <input type="text" id="sv1_lop" name="sv1_lop" class="form-control">
                </div>
            </div>

            <!-- Sinh viên 2 (Nếu có) -->
            <h3 style="margin-top: 24px; margin-bottom: 16px; color: #1f2937;">Sinh viên 2 (Nếu có)</h3>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="sv2_hoten">Họ tên Sinh viên 2</label>
                    <input type="text" id="sv2_hoten" name="sv2_hoten" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="sv2_mssv">MSSV 2</label>
                    <input type="text" id="sv2_mssv" name="sv2_mssv" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="sv2_lop">Lớp 2</label>
                    <input type="text" id="sv2_lop" name="sv2_lop" class="form-control">
                </div>
            </div>

            <!-- Thông tin Đề tài -->
            <h3 style="margin-top: 24px; margin-bottom: 16px; color: #1f2937;">Thông tin Đề tài</h3>
            <div class="form-group">
                <label for="ten_detai">Tiêu đề (Đề tài) <span class="required">*</span></label>
                <input type="text" id="ten_detai" name="ten_detai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="nhiem_vu">Nhiệm vụ (Nội dung và số liệu ban đầu) <span class="required">*</span></label>
                <textarea id="nhiem_vu" name="nhiem_vu" class="form-control" rows="6" required placeholder="Mỗi nhiệm vụ trên một dòng"></textarea>
                <small class="form-text text-muted">Mỗi nhiệm vụ trên một dòng</small>
            </div>

            <div class="form-group">
                <label for="ho_so_tai_lieu">Các hồ sơ và tài liệu cung cấp ban đầu</label>
                <textarea id="ho_so_tai_lieu" name="ho_so_tai_lieu" class="form-control" rows="4" placeholder="Mỗi tài liệu trên một dòng"></textarea>
                <small class="form-text text-muted">Mỗi tài liệu trên một dòng</small>
            </div>

            <!-- Giảng viên Hướng dẫn -->
            <h3 style="margin-top: 24px; margin-bottom: 16px; color: #1f2937;">Giảng viên Hướng dẫn</h3>
            <div class="form-group">
                <label for="gv_hoten">Họ tên Giảng viên Hướng dẫn <span class="required">*</span></label>
                <input type="text" id="gv_hoten" name="gv_hoten" class="form-control" required>
            </div>

            <!-- Ngày tháng -->
            <h3 style="margin-top: 24px; margin-bottom: 16px; color: #1f2937;">Ngày tháng</h3>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ngay_giao">Ngày giao nhiệm vụ</label>
                    <input type="date" id="ngay_giao" name="ngay_giao" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="ngay_hoan_thanh">Ngày hoàn thành nhiệm vụ</label>
                    <input type="date" id="ngay_hoan_thanh" name="ngay_hoan_thanh" class="form-control">
                </div>
            </div>

            <!-- Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    📄 Xuất file Word
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: flex-start; 
        margin-bottom: 24px; 
    }
    .page-title { 
        font-size: 24px; 
        font-weight: 700; 
        color: #1f2937; 
        margin: 0; 
    }
    .page-subtitle { 
        margin: 8px 0 0; 
        color: #6b7280; 
        font-size: 14px; 
    }
    .card { 
        background: #fff; 
        border: 1px solid #e5e7eb; 
        border-radius: 8px; 
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .card-body { 
        padding: 24px; 
    }
    .form-group { 
        margin-bottom: 20px; 
    }
    .form-group label { 
        font-weight: 600; 
        display: block; 
        margin-bottom: 8px; 
        color: #374151; 
        font-size: 14px;
    }
    .form-control { 
        width: 100%; 
        padding: 10px 14px; 
        border: 1px solid #d1d5db; 
        border-radius: 6px; 
        font-size: 14px; 
        transition: all 0.2s;
    }
    .form-control:focus { 
        outline: none; 
        border-color: #4f46e5; 
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); 
    }
    .form-control::placeholder {
        color: #9ca3af;
    }
    .form-row { 
        display: flex; 
        gap: 16px; 
        flex-wrap: wrap;
    }
    .form-row .form-group { 
        flex: 1; 
        min-width: 200px;
    }
    .required { 
        color: #dc2626; 
        margin-left: 2px;
    }
    .form-text { 
        font-size: 12px; 
        margin-top: 6px; 
        color: #6b7280;
    }
    .form-actions { 
        margin-top: 32px; 
        padding-top: 24px; 
        border-top: 2px solid #e5e7eb; 
        display: flex;
        justify-content: flex-end;
    }
    .btn { 
        padding: 12px 24px; 
        border: none; 
        border-radius: 6px; 
        cursor: pointer; 
        color: #fff; 
        font-weight: 600; 
        font-size: 14px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary { 
        background: #4f46e5; 
    }
    .btn-primary:hover { 
        background: #4338ca; 
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
    }
    .alert { 
        padding: 12px 16px; 
        border-radius: 6px; 
        margin-bottom: 16px; 
    }
    .alert-error { 
        background: #fee2e2; 
        color: #b91c1c; 
        border: 1px solid #fecaca; 
    }
    .alert-success { 
        background: #dcfce7; 
        color: #166534; 
        border: 1px solid #bbf7d0; 
    }
    h3 { 
        font-size: 18px; 
        font-weight: 600;
        margin-bottom: 16px;
        color: #1f2937;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
    }
    hr {
        margin: 24px 0;
        border: none;
        border-top: 1px solid #e5e7eb;
    }
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    select.form-control {
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
        .form-row .form-group {
            min-width: 100%;
        }
    }
</style>

<script>
// Dữ liệu nhóm và sinh viên từ server
const nhomData = @json($nhomData ?? []);

function loadNhomData(nhomId) {
    if (!nhomId) return;
    
    const nhom = nhomData.find(n => n.id == nhomId);
    if (!nhom) return;

    // Điền thông tin sinh viên 1
    if (nhom.sv1) {
        document.getElementById('sv1_hoten').value = nhom.sv1.hoten || '';
        document.getElementById('sv1_mssv').value = nhom.sv1.mssv || '';
        document.getElementById('sv1_lop').value = nhom.sv1.lop || '';
    }

    // Điền thông tin sinh viên 2
    if (nhom.sv2) {
        document.getElementById('sv2_hoten').value = nhom.sv2.hoten || '';
        document.getElementById('sv2_mssv').value = nhom.sv2.mssv || '';
        document.getElementById('sv2_lop').value = nhom.sv2.lop || '';
    } else {
        document.getElementById('sv2_hoten').value = '';
        document.getElementById('sv2_mssv').value = '';
        document.getElementById('sv2_lop').value = '';
    }

    // Điền thông tin đề tài
    if (nhom.detai) {
        document.getElementById('ten_detai').value = nhom.detai.ten_detai || '';
        // Có thể điền nhiệm vụ từ mô tả nếu có
        if (nhom.detai.mo_ta) {
            document.getElementById('nhiem_vu').value = nhom.detai.mo_ta;
        }
    }

    // Điền thông tin giảng viên
    if (nhom.gv) {
        document.getElementById('gv_hoten').value = nhom.gv.hoten || '';
    }
}
</script>
@endsection

