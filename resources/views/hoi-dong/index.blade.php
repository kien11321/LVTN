@extends('layouts.app')

@section('title', 'Hội đồng LVTN')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Hội đồng LVTN</h1>
        <p class="page-subtitle">- Hiện ra danh sách hội đồng</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('hoi-dong.export') }}" class="btn btn-success" style="margin-right: 10px;">⬇️ Xuất Excel</a>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">+ Tạo hội đồng</button>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên hội đồng</th>
                    <th>Chủ tịch</th>
                    <th>Thư ký</th>
                    <th>Ủy viên 1</th>
                    <th>Ủy viên 2</th>
                    <th>Phòng</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hoiDongs as $index => $hoiDong)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $hoiDong->ten_hoi_dong }}</strong></td>
                        <td>{{ $hoiDong->chuTich->hoten ?? '-' }}</td>
                        <td>{{ $hoiDong->thuKy->hoten ?? '-' }}</td>
                        <td>{{ $hoiDong->uyVien1->hoten ?? '-' }}</td>
                        <td>{{ $hoiDong->uyVien2->hoten ?? '-' }}</td>
                        <td>{{ $hoiDong->phong_bao_ve ?? '-' }}</td>
                        <td>
                            {{ $hoiDong->ngay_bao_ve->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn-icon btn-edit" onclick="openEditModal({{ $hoiDong->id }})" title="Sửa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0 .11.168l10 10a.5.5 0 0 0 .708-.708l-10-10a.5.5 0 0 0-.168-.11z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('hoi-dong.destroy', $hoiDong->id) }}" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa hội đồng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" title="Xóa">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Chưa có hội đồng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Danh sách đề tài đã gán vào hội đồng -->
@php
    $hasDeTaiGan = false;
    foreach($hoiDongs as $hoiDong) {
        if($hoiDong->deTais->isNotEmpty()) {
            $hasDeTaiGan = true;
            break;
        }
    }
@endphp

@if($hasDeTaiGan)
    <div class="card" style="margin-top: 30px;">
        <h3 style="margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #6d28d9;">Đề tài đã gán vào hội đồng</h3>
        @foreach($hoiDongs as $hoiDong)
            @if($hoiDong->deTais->isNotEmpty())
                <div style="margin-bottom: 25px; padding: 15px; background: #f9fafb; border-radius: 6px;">
                    <h4 style="margin: 0 0 10px 0; color: #6d28d9;">
                        <strong>{{ $hoiDong->ten_hoi_dong }}</strong>
                        <span style="font-size: 14px; color: #666; font-weight: normal;">({{ $hoiDong->deTais->count() }} đề tài)</span>
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 10px;">
                        @foreach($hoiDong->deTais as $deTai)
                            <div style="padding: 10px; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px;">
                                <div style="font-weight: 600; margin-bottom: 5px;">{{ $deTai->ten_detai }}</div>
                                <div style="font-size: 13px; color: #666;">
                                    <div>Nhóm: {{ $deTai->nhomSinhVien->ten_nhom ?? '-' }}</div>
                                    <div>GVHD: {{ $deTai->giangVien->hoten ?? '-' }}</div>
                                    @if($deTai->nhomSinhVien && $deTai->nhomSinhVien->sinhViens)
                                        <div>SV: 
                                            @foreach($deTai->nhomSinhVien->sinhViens as $sv)
                                                {{ $sv->hoten }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('hoi-dong.unassign-detai') }}" style="margin-top: 8px;" onsubmit="return confirm('Bạn có chắc muốn hủy gán đề tài này?');">
                                    @csrf
                                    <input type="hidden" name="detai_id" value="{{ $deTai->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 10px; font-size: 12px;">Hủy gán</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="card" style="margin-top: 30px; padding: 20px; text-align: center; background: #fef3c7; border: 1px solid #fbbf24;">
        <p style="margin: 0; color: #92400e; font-size: 16px;">
            <strong>Chưa có đề tài nào được gán vào hội đồng.</strong><br>
            <span style="font-size: 14px;">Vui lòng gán đề tài từ danh sách "Đề tài chưa gán vào hội đồng" bên dưới.</span>
        </p>
    </div>
@endif

<!-- Danh sách đề tài chưa gán -->
@if($deTaisChuaGan->isNotEmpty())
    <div class="card" style="margin-top: 30px;">
        <h3 style="margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #ef4444;">Đề tài chưa gán vào hội đồng ({{ $deTaisChuaGan->count() }} đề tài)</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 10px;">
            @foreach($deTaisChuaGan as $deTai)
                <div style="padding: 10px; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px;">
                    <div style="font-weight: 600; margin-bottom: 5px;">{{ $deTai->ten_detai }}</div>
                    <div style="font-size: 13px; color: #666; margin-bottom: 10px;">
                        <div>Nhóm: {{ $deTai->nhomSinhVien->ten_nhom ?? '-' }}</div>
                        <div>GVHD: {{ $deTai->giangVien->hoten ?? '-' }}</div>
                        @if($deTai->nhomSinhVien && $deTai->nhomSinhVien->sinhViens)
                            <div>SV: 
                                @foreach($deTai->nhomSinhVien->sinhViens as $sv)
                                    {{ $sv->hoten }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if($hoiDongs->isNotEmpty())
                        <form method="POST" action="{{ route('hoi-dong.assign-detai') }}">
                            @csrf
                            <input type="hidden" name="detai_id" value="{{ $deTai->id }}">
                            <select name="hoi_dong_id" class="form-control" style="width: 100%; margin-bottom: 8px;" required>
                                <option value="">-- Chọn hội đồng --</option>
                                @foreach($hoiDongs as $hd)
                                    <option value="{{ $hd->id }}">{{ $hd->ten_hoi_dong }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary" style="padding: 4px 10px; font-size: 12px; width: 100%;">Gán vào hội đồng</button>
                        </form>
                    @else
                        <p style="color: #ef4444; font-size: 12px; margin: 0;">Vui lòng tạo hội đồng trước khi gán đề tài.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="card" style="margin-top: 30px; padding: 20px; text-align: center; background: #dbeafe; border: 1px solid #3b82f6;">
        <p style="margin: 0; color: #1e40af; font-size: 16px;">
            <strong>Không có đề tài nào chưa được gán vào hội đồng.</strong><br>
            <span style="font-size: 14px;">Tất cả đề tài đã được gán hoặc chưa có đề tài nào có nhóm sinh viên.</span>
        </p>
    </div>
@endif

<!-- Modal Tạo/Sửa Hội đồng -->
<div id="hoiDongModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Tạo hội đồng mới</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="hoiDongForm" method="POST">
            @csrf
            <div id="methodField"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ten_hoi_dong">Tên hội đồng <span class="required">*</span></label>
                    <input type="text" id="ten_hoi_dong" name="ten_hoi_dong" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="ngay_bao_ve">Ngày giờ bảo vệ <span class="required">*</span></label>
                    <input type="date" id="ngay_bao_ve" name="ngay_bao_ve" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phong_bao_ve">Phòng bảo vệ</label>
                    <input type="text" id="phong_bao_ve" name="phong_bao_ve" class="form-control">
                </div>
                <div class="form-group">
                    <label for="chu_tich_id">Chủ tịch <span class="required">*</span></label>
                    <select id="chu_tich_id" name="chu_tich_id" class="form-control" required onchange="updateGiangVienOptions()">
                        <option value="">-- Chọn giảng viên --</option>
                        @foreach($giangViens as $gv)
                            <option value="{{ $gv->id }}" data-name="{{ $gv->hoten }}">{{ $gv->hoten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="thu_ky_id">Thư ký <span class="required">*</span></label>
                    <select id="thu_ky_id" name="thu_ky_id" class="form-control" required onchange="updateGiangVienOptions()">
                        <option value="">-- Chọn giảng viên --</option>
                        @foreach($giangViens as $gv)
                            <option value="{{ $gv->id }}" data-name="{{ $gv->hoten }}">{{ $gv->hoten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="uy_vien_1_id">Ủy viên 1</label>
                    <select id="uy_vien_1_id" name="uy_vien_1_id" class="form-control" onchange="updateGiangVienOptions()">
                        <option value="">-- Chọn giảng viên --</option>
                        @foreach($giangViens as $gv)
                            <option value="{{ $gv->id }}" data-name="{{ $gv->hoten }}">{{ $gv->hoten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="uy_vien_2_id">Ủy viên 2</label>
                    <select id="uy_vien_2_id" name="uy_vien_2_id" class="form-control" onchange="updateGiangVienOptions()">
                        <option value="">-- Chọn giảng viên --</option>
                        @foreach($giangViens as $gv)
                            <option value="{{ $gv->id }}" data-name="{{ $gv->hoten }}">{{ $gv->hoten }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu hội đồng</button>
            </div>
        </form>
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
        background: #6d28d9;
        color: #fff;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #5b21b6;
        font-weight: 600;
    }
    tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit {
        background: #fbbf24;
        color: #78350f;
    }
    .btn-edit:hover {
        background: #f59e0b;
    }
    .btn-delete {
        background: #ef4444;
        color: #fff;
    }
    .btn-delete:hover {
        background: #dc2626;
    }
    .btn-print {
        background: #3b82f6;
        color: #fff;
    }
    .btn-print:hover {
        background: #2563eb;
    }
    .inline-form {
        display: inline;
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
    .text-center {
        text-align: center;
    }
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .modal-header {
        background: #6d28d9;
        color: #fff;
        padding: 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h2 {
        margin: 0;
        font-size: 20px;
    }
    .close {
        color: #fff;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }
    .close:hover {
        opacity: 0.7;
    }
    .modal-body {
        padding: 20px;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #374151;
    }
    .required {
        color: #ef4444;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    .form-control:focus {
        outline: none;
        border-color: #6d28d9;
        box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.1);
    }
    .form-hint {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 500;
    }
    .modal-footer {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-primary {
        background: #6d28d9;
        color: #fff;
    }
    .btn-primary:hover {
        background: #5b21b6;
    }
    .btn-secondary {
        background: #6b7280;
        color: #fff;
    }
    .btn-secondary:hover {
        background: #4b5563;
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
    .btn-danger {
        background: #ef4444;
        color: #fff;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
    .btn-success {
        background: #16a34a;
        color: #fff;
    }
    .btn-success:hover {
        background: #15803d;
    }
</style>

<script>
    const hoiDongs = @json($hoiDongs);

    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Tạo hội đồng mới';
        document.getElementById('hoiDongForm').action = '{{ route("hoi-dong.store") }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('hoiDongForm').reset();
        document.getElementById('hoiDongModal').style.display = 'block';
    }

    function openEditModal(id) {
        const hoiDong = hoiDongs.find(h => h.id === id);
        if (!hoiDong) return;

        document.getElementById('modalTitle').textContent = 'Sửa hội đồng';
        document.getElementById('hoiDongForm').action = '{{ route("hoi-dong.update", ":id") }}'.replace(':id', id);
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        document.getElementById('ten_hoi_dong').value = hoiDong.ten_hoi_dong || '';
        
        // Format ngày cho input type="date" (YYYY-MM-DD)
        let ngayBaoVe = '';
        if (hoiDong.ngay_bao_ve) {
            if (typeof hoiDong.ngay_bao_ve === 'string') {
                // Nếu là string, format từ YYYY-MM-DD hoặc DD/MM/YYYY
                const dateStr = hoiDong.ngay_bao_ve;
                if (dateStr.includes('/')) {
                    // DD/MM/YYYY -> YYYY-MM-DD
                    const parts = dateStr.split('/');
                    ngayBaoVe = `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else {
                    ngayBaoVe = dateStr.split(' ')[0]; // Lấy phần ngày nếu có time
                }
            } else if (hoiDong.ngay_bao_ve.year) {
                // Nếu là Carbon object được serialize
                ngayBaoVe = `${hoiDong.ngay_bao_ve.year}-${String(hoiDong.ngay_bao_ve.month).padStart(2, '0')}-${String(hoiDong.ngay_bao_ve.day).padStart(2, '0')}`;
            }
        }
        document.getElementById('ngay_bao_ve').value = ngayBaoVe;
        
        document.getElementById('phong_bao_ve').value = hoiDong.phong_bao_ve || '';
        document.getElementById('chu_tich_id').value = hoiDong.chu_tich_id || '';
        document.getElementById('thu_ky_id').value = hoiDong.thu_ky_id || '';
        document.getElementById('uy_vien_1_id').value = hoiDong.uy_vien_1_id || '';
        document.getElementById('uy_vien_2_id').value = hoiDong.uy_vien_2_id || '';
        
        document.getElementById('hoiDongModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('hoiDongModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('hoiDongModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    // Hàm cập nhật options của các select giảng viên
    function updateGiangVienOptions() {
        const chuTichId = document.getElementById('chu_tich_id').value;
        const thuKyId = document.getElementById('thu_ky_id').value;
        const uyVien1Id = document.getElementById('uy_vien_1_id').value;
        const uyVien2Id = document.getElementById('uy_vien_2_id').value;

        // Danh sách ID đã được chọn
        const selectedIds = [chuTichId, thuKyId, uyVien1Id, uyVien2Id].filter(id => id !== '');

        // Cập nhật từng select
        updateSelectOptions('chu_tich_id', chuTichId, selectedIds);
        updateSelectOptions('thu_ky_id', thuKyId, selectedIds);
        updateSelectOptions('uy_vien_1_id', uyVien1Id, selectedIds);
        updateSelectOptions('uy_vien_2_id', uyVien2Id, selectedIds);
    }

    function updateSelectOptions(selectId, currentValue, selectedIds) {
        const select = document.getElementById(selectId);
        const options = select.querySelectorAll('option');

        options.forEach(option => {
            const optionValue = option.value;
            
            // Giữ lại option rỗng và option đang được chọn
            if (optionValue === '' || optionValue === currentValue) {
                option.style.display = '';
                return;
            }

            // Ẩn option nếu đã được chọn ở vị trí khác
            if (selectedIds.includes(optionValue) && optionValue !== currentValue) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    }

    // Gọi hàm khi mở modal để cập nhật options
    const originalOpenCreateModal = openCreateModal;
    openCreateModal = function() {
        originalOpenCreateModal();
        setTimeout(updateGiangVienOptions, 100);
    };

    const originalOpenEditModal = openEditModal;
    openEditModal = function(id) {
        originalOpenEditModal(id);
        setTimeout(updateGiangVienOptions, 100);
    };
</script>
@endsection

