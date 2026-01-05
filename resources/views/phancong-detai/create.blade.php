@extends('layouts.app')

@section('title', 'Phân công đề tài')

@section('content')
    <div class="page-phancong-detai-assign">

        <div class="page-header">
            <h1 class="page-title">Phân công đề tài cho {{ $nhom->ten_nhom }}</h1>
            <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="layout-row">
            <!-- CỘT TRÁI -->
            <div class="col-left">
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
                                @foreach ($nhom->sinhViens as $sv)
                                    <li>{{ $sv->hoten }} ({{ $sv->mssv }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI -->
            <div class="col-right">
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
                                <label for="detai_select">
                                    Chọn đề tài <span class="required">*</span>
                                </label>
                                <select id="detai_select" class="form-control" onchange="selectFromDropdown(this)">
                                    <option value="">-- Chọn đề tài --</option>
                                    @foreach ($deTais as $dt)
                                        <option value="{{ $dt->id }}" data-ten="{{ $dt->ten_detai }}"
                                            data-mota="{{ $dt->mo_ta }}"
                                            data-giangvien="{{ $dt->giangVien ? $dt->giangVien->hoten : 'Chưa có' }}"
                                            data-loai="{{ $dt->loai }}">
                                            {{ $dt->ten_detai }} - GV:
                                            {{ $dt->giangVien ? $dt->giangVien->hoten : 'Chưa có' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($deTais->isEmpty())
                                <div class="alert alert-warning">
                                    ⚠️ Không có đề tài nào còn trống.
                                    <a href="{{ route('phancong-detai.create-detai') }}">Tạo đề tài mới</a>
                                </div>
                            @endif

                            <div id="selected_detai_display" class="selected-detai hidden">
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
                                <a href="{{ route('phancong-detai.index') }}" class="btn btn-secondary">
                                    Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const selectedDisplay = document.getElementById('selected_detai_display');
            const submitBtn = document.getElementById('submitBtn');

            function selectFromDropdown(select) {
                const option = select.options[select.selectedIndex];
                if (!option.value) {
                    clearSelection();
                    return;
                }

                selectDeTai(
                    option.value,
                    option.dataset.ten,
                    option.dataset.mota,
                    option.dataset.giangvien,
                    option.dataset.loai
                );
            }

            function selectDeTai(id, ten, moTa, giangvien, loai) {
                document.getElementById('selected_detai_id').value = id;
                document.getElementById('display_ten_detai').textContent = ten;
                document.getElementById('display_mo_ta').textContent = moTa || 'Không có mô tả';
                document.getElementById('display_giangvien').textContent = 'GV: ' + giangvien;
                document.getElementById('display_loai').textContent = loai === 'nhom' ? 'Nhóm' : 'Cá nhân';

                selectedDisplay.classList.remove('hidden');
                submitBtn.disabled = false;
            }

            function clearSelection() {
                document.getElementById('selected_detai_id').value = '';
                document.getElementById('detai_select').value = '';
                selectedDisplay.classList.add('hidden');
                submitBtn.disabled = true;
            }
        </script>

    </div>
@endsection
