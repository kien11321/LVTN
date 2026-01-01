@extends('layouts.app')

@section('title', 'Hội đồng LVTN')

@section('content')
<div class="page-hoidong">

    <div class="page-header">
        <div>
            <h1 class="page-title">Hội đồng LVTN</h1>
            <p class="page-subtitle">- Hiện ra danh sách hội đồng</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('hoi-dong.export') }}" class="btn btn-success btn-export">⬇️ Xuất Excel</a>
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
                            <td>{{ $hoiDong->ngay_bao_ve->format('d/m/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-icon btn-edit"
                                            onclick="openEditModal({{ $hoiDong->id }})" title="Sửa">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0 .11.168l10 10a.5.5 0 0 0 .708-.708l-10-10a.5.5 0 0 0-.168-.11z"/>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('hoi-dong.destroy', $hoiDong->id) }}"
                                          class="inline-form"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa hội đồng này?');">
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

    {{-- Danh sách đề tài đã gán vào hội đồng --}}
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
        <div class="card card--mt">
            <h3 class="section-title section-title--purple">Đề tài đã gán vào hội đồng</h3>

            @foreach($hoiDongs as $hoiDong)
                @if($hoiDong->deTais->isNotEmpty())
                    <div class="assigned-block">
                        <h4 class="assigned-title">
                            <strong>{{ $hoiDong->ten_hoi_dong }}</strong>
                            <span class="assigned-count">({{ $hoiDong->deTais->count() }} đề tài)</span>
                        </h4>

                        <div class="topic-grid">
                            @foreach($hoiDong->deTais as $deTai)
                                <div class="topic-card">
                                    <div class="topic-name">{{ $deTai->ten_detai }}</div>

                                    <div class="topic-meta">
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

                                    <form method="POST" action="{{ route('hoi-dong.unassign-detai') }}"
                                          class="unassign-form"
                                          onsubmit="return confirm('Bạn có chắc muốn hủy gán đề tài này?');">
                                        @csrf
                                        <input type="hidden" name="detai_id" value="{{ $deTai->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Hủy gán</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="card card--mt card--warn">
            <p class="notice-text notice-text--warn">
                <strong>Chưa có đề tài nào được gán vào hội đồng.</strong><br>
                <span>Vui lòng gán đề tài từ danh sách "Đề tài chưa gán vào hội đồng" bên dưới.</span>
            </p>
        </div>
    @endif

    {{-- Danh sách đề tài chưa gán --}}
    @if($deTaisChuaGan->isNotEmpty())
        <div class="card card--mt">
            <h3 class="section-title section-title--red">
                Đề tài chưa gán vào hội đồng ({{ $deTaisChuaGan->count() }} đề tài)
            </h3>

            <div class="topic-grid">
                @foreach($deTaisChuaGan as $deTai)
                    <div class="topic-card">
                        <div class="topic-name">{{ $deTai->ten_detai }}</div>

                        <div class="topic-meta topic-meta--mb">
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

                                <select name="hoi_dong_id" class="form-control form-control--mb" required>
                                    <option value="">-- Chọn hội đồng --</option>
                                    @foreach($hoiDongs as $hd)
                                        <option value="{{ $hd->id }}">{{ $hd->ten_hoi_dong }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary btn-full">Gán vào hội đồng</button>
                            </form>
                        @else
                            <p class="no-council-text">Vui lòng tạo hội đồng trước khi gán đề tài.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card card--mt card--info">
            <p class="notice-text notice-text--info">
                <strong>Không có đề tài nào chưa được gán vào hội đồng.</strong><br>
                <span>Tất cả đề tài đã được gán hoặc chưa có đề tài nào có nhóm sinh viên.</span>
            </p>
        </div>
    @endif

    {{-- Modal Tạo/Sửa Hội đồng --}}
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

            let ngayBaoVe = '';
            if (hoiDong.ngay_bao_ve) {
                if (typeof hoiDong.ngay_bao_ve === 'string') {
                    const dateStr = hoiDong.ngay_bao_ve;
                    if (dateStr.includes('/')) {
                        const parts = dateStr.split('/');
                        ngayBaoVe = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    } else {
                        ngayBaoVe = dateStr.split(' ')[0];
                    }
                } else if (hoiDong.ngay_bao_ve.year) {
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
            if (event.target === modal) closeModal();
        }

        function updateGiangVienOptions() {
            const chuTichId = document.getElementById('chu_tich_id').value;
            const thuKyId = document.getElementById('thu_ky_id').value;
            const uyVien1Id = document.getElementById('uy_vien_1_id').value;
            const uyVien2Id = document.getElementById('uy_vien_2_id').value;

            const selectedIds = [chuTichId, thuKyId, uyVien1Id, uyVien2Id].filter(id => id !== '');

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

                if (optionValue === '' || optionValue === currentValue) {
                    option.style.display = '';
                    return;
                }

                if (selectedIds.includes(optionValue) && optionValue !== currentValue) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        }

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

</div>
@endsection
