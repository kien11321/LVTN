@extends('layouts.app')

@section('title', 'Tạo Phiếu Giao Đề Tài')

@section('content')
    <div class="page-tao-phieu-giao-detai">

        <div class="page-header">
            <div>
                <h1 class="page-title">Tạo Phiếu Giao Đề Tài</h1>
                <p class="page-subtitle">Nhập thông tin bên dưới để xuất ra file Word theo template.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
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
                            @if (isset($nhoms) && $nhoms->count() > 0)
                                @foreach ($nhoms as $nhom)
                                    <option value="{{ $nhom->id }}"
                                        data-sv1="{{ $nhom->sinhViens && $nhom->sinhViens->first() ? $nhom->sinhViens->first()->id : '' }}"
                                        data-sv2="{{ $nhom->sinhViens && $nhom->sinhViens->count() > 1 ? $nhom->sinhViens->skip(1)->first()->id : '' }}"
                                        data-detai="{{ $nhom->deTai ? $nhom->deTai->id : '' }}"
                                        data-gv="{{ $nhom->deTai && $nhom->deTai->giangVien ? $nhom->deTai->giangVien->id : '' }}">
                                        {{ $nhom->ten_nhom }} -
                                        {{ $nhom->deTai && $nhom->deTai->ten_detai ? $nhom->deTai->ten_detai : 'Chưa có đề tài' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Không có nhóm nào có đề tài</option>
                            @endif
                        </select>
                    </div>

                    <hr class="divider">

                    <!-- Sinh viên 1 -->
                    <h3 class="section-title">Sinh viên 1</h3>
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
                    <h3 class="section-title mt-24">Sinh viên 2 (Nếu có)</h3>
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
                    <h3 class="section-title mt-24">Thông tin Đề tài</h3>
                    <div class="form-group">
                        <label for="ten_detai">Tiêu đề (Đề tài) <span class="required">*</span></label>
                        <input type="text" id="ten_detai" name="ten_detai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nhiem_vu">Nhiệm vụ (Nội dung và số liệu ban đầu) <span class="required">*</span></label>
                        <textarea id="nhiem_vu" name="nhiem_vu" class="form-control" rows="6" required
                            placeholder="Mỗi nhiệm vụ trên một dòng"></textarea>
                        <small class="form-text">Mỗi nhiệm vụ trên một dòng</small>
                    </div>

                    <div class="form-group">
                        <label for="ho_so_tai_lieu">Các hồ sơ và tài liệu cung cấp ban đầu</label>
                        <textarea id="ho_so_tai_lieu" name="ho_so_tai_lieu" class="form-control" rows="4"
                            placeholder="Mỗi tài liệu trên một dòng"></textarea>
                        <small class="form-text">Mỗi tài liệu trên một dòng</small>
                    </div>

                    <!-- Giảng viên Hướng dẫn -->
                    <h3 class="section-title mt-24">Giảng viên Hướng dẫn</h3>
                    <div class="form-group">
                        <label for="gv_hoten">Họ tên Giảng viên Hướng dẫn <span class="required">*</span></label>
                        <input type="text" id="gv_hoten" name="gv_hoten" class="form-control" required>
                    </div>

                    <!-- Ngày tháng -->
                    <h3 class="section-title mt-24">Ngày tháng</h3>
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

    </div>

    <script>
        const nhomData = @json($nhomData ?? []);

        function loadNhomData(nhomId) {
            if (!nhomId) return;

            const nhom = nhomData.find(n => n.id == nhomId);
            if (!nhom) return;

            // SV1
            if (nhom.sv1) {
                document.getElementById('sv1_hoten').value = nhom.sv1.hoten || '';
                document.getElementById('sv1_mssv').value = nhom.sv1.mssv || '';
                document.getElementById('sv1_lop').value = nhom.sv1.lop || '';
            }

            // SV2
            if (nhom.sv2) {
                document.getElementById('sv2_hoten').value = nhom.sv2.hoten || '';
                document.getElementById('sv2_mssv').value = nhom.sv2.mssv || '';
                document.getElementById('sv2_lop').value = nhom.sv2.lop || '';
            } else {
                document.getElementById('sv2_hoten').value = '';
                document.getElementById('sv2_mssv').value = '';
                document.getElementById('sv2_lop').value = '';
            }

            // Đề tài
            if (nhom.detai) {
                document.getElementById('ten_detai').value = nhom.detai.ten_detai || '';
                if (nhom.detai.mo_ta) {
                    document.getElementById('nhiem_vu').value = nhom.detai.mo_ta;
                }
            }

            // GVHD
            if (nhom.gv) {
                document.getElementById('gv_hoten').value = nhom.gv.hoten || '';
            }
        }
    </script>
@endsection
