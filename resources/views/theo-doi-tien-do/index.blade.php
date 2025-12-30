@extends('layouts.app')

@section('title', 'Theo dõi Tiến độ & Đánh giá Giữa kỳ')


@section('content')
    <div class="page-header">
        <h1 class="page-title">Theo dõi Tiến độ & Đánh giá Giữa kỳ</h1>
        <p class="page-subtitle">Danh sách sinh viên thuộc nhóm hướng dẫn và cập nhật tiến độ thực hiện.</p>
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
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
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
                    <th>Nhóm</th>
                    <th>Tiến độ GK</th>
                    <th>Quyết định</th>
                    <th>Ghi chú GK</th>
                    @if(!$isAdmin)  <th>Hành động</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($nhomGroups as $nhomId => $sinhViens)
                    @php
                        // Kiểm tra collection không rỗng trước khi gọi first()
                        $firstSV = $sinhViens->isNotEmpty() ? $sinhViens->first() : null;
                        $tenNhom = $firstSV && isset($firstSV->ten_nhom) ? $firstSV->ten_nhom : 'Nhóm ' . $nhomId;
                        $tenDetai = $firstSV && isset($firstSV->ten_detai) ? $firstSV->ten_detai : '-';
                        $tienDo = $firstSV && isset($firstSV->tien_do) ? $firstSV->tien_do : null;
                        $quyetDinh = $firstSV && isset($firstSV->quyet_dinh) ? $firstSV->quyet_dinh : 'duoc_lam_tiep';
                        $ghiChu = $firstSV && isset($firstSV->ghi_chu) ? $firstSV->ghi_chu : '';
                        $labels = [
                            'duoc_lam_tiep' => ['text' => 'Được làm tiếp', 'class' => 'quyet-dinh-success'],
                            'tam_dung' => ['text' => 'Tạm dừng', 'class' => 'quyet-dinh-warning'],
                            'huy' => ['text' => 'Hủy', 'class' => 'quyet-dinh-danger'],
                        ];
                        $label = $labels[$quyetDinh] ?? ['text' => '-', 'class' => ''];
                    @endphp
                    @foreach ($sinhViens as $index => $sv)
                        <tr class="sinh-vien-row">
                            <!-- Cột thông tin sinh viên - có border riêng -->
                            <td class="sv-info-cell">{{ $sv->mssv }}</td>
                            <td class="sv-info-cell">{{ $sv->hoten }}</td>
                            <td class="sv-info-cell">{{ $sv->lop }}</td>
                            <td class="sv-info-cell">{{ $sv->email }}</td>
                            <td class="sv-info-cell">{{ $sv->sdt }}</td>

                            <!-- Cột nhóm/đề tài - merge cells, không có border giữa các SV cùng nhóm -->
                            @if ($index === 0)
                                <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                    <strong>{{ $tenDetai }}</strong>
                                </td>
                                <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                    <strong>{{ $tenNhom }}</strong>
                                </td>
                                <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                    @if ($tienDo !== null)
                                        <span class="tien-do-badge">{{ $tienDo }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                    @if ($quyetDinh)
                                        <span class="quyet-dinh-badge {{ $label['class'] }}">{{ $label['text'] }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                    @if ($ghiChu)
                                        {{ Str::limit($ghiChu, 30) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                  @if(!$isAdmin)
                                    <td class="nhom-cell" rowspan="{{ $sinhViens->count() }}">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="openUpdateModal({{ $nhomId }}, '{{ addslashes($tenNhom) }}', {{ $tienDo ?? 0 }}, '{{ $quyetDinh }}', '{{ addslashes($ghiChu) }}')">
                                            ✏️ Đánh Giá
                                        </button>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Không có sinh viên nào có nhóm</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Cập nhật Tiến độ -->
    <div id="updateModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Cập nhật Tiến độ Giữa kỳ</h2>
                <span class="close" onclick="closeUpdateModal()">&times;</span>
            </div>
            <form method="POST" id="updateForm" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nhóm:</label>
                        <div class="nhom-info" id="nhom-info"
                            style="padding: 10px; background: #f8f9fa; border-radius: 4px; font-weight: 600; color: #007bff;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tien_do">Tiến độ hoàn thành (0-100%):</label>
                        <input type="number" id="tien_do" name="tien_do" class="form-control" min="0"
                            max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="quyet_dinh">Quyết định:</label>
                        <select id="quyet_dinh" name="quyet_dinh" class="form-control" required>
                            <option value="duoc_lam_tiep">Được làm tiếp</option>
                            <option value="tam_dung">Tạm dừng</option>
                            <option value="huy">Hủy</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ghi_chu">Ghi chú / Nhận xét (nếu có):</label>
                        <textarea id="ghi_chu" name="ghi_chu" class="form-control" rows="4" placeholder="Nhập ghi chú hoặc nhận xét..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeUpdateModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .page-actions {
            display: flex;
            align-items: center;
        }

        .page-subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .tien-do-badge {
            color: #007bff;
            font-weight: 600;
        }

        .quyet-dinh-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .quyet-dinh-success {
            background: #d4edda;
            color: #155724;
        }

        .quyet-dinh-warning {
            background: #fff3cd;
            color: #856404;
        }

        .quyet-dinh-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .text-muted {
            color: #6c757d;
        }

        .nhom-info {
            font-size: 16px;
        }

        thead th:nth-child(7),
        thead th:nth-child(8),
        thead th:nth-child(9),
        thead th:nth-child(10),
        thead th:nth-child(11) {
            background-color: #2c3e50;
        }

        /* Border cho cột thông tin sinh viên - mỗi SV có border riêng */
        .sv-info-cell {
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd;
            background-color: #fff;
        }

        /* Cột nhóm/đề tài - không có border giữa các SV cùng nhóm */
        .nhom-cell {
            border-right: 1px solid #ddd;
            /* border-left: 3px solid #007bff; */
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
            /* background-color: #f8f9fa; */
            text-align: center;
        }

        /* Tách biệt rõ ràng giữa phần thông tin SV và phần nhóm */
        /* .sv-info-cell:last-of-type {
                        border-right: 3px solid #007bff;
                    } */

        /* Khoảng cách giữa các nhóm */
        tbody tr:last-child td {
            border-bottom: 2px solid #007bff;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
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

        textarea.form-control {
            resize: vertical;
        }
    </style>

    <script>
        function openUpdateModal(nhomId, tenNhom, tienDo, quyetDinh, ghiChu) {
            document.getElementById('updateForm').action = `/theo-doi-tien-do/${nhomId}`;
            document.getElementById('nhom-info').textContent = tenNhom;
            document.getElementById('tien_do').value = tienDo;
            document.getElementById('quyet_dinh').value = quyetDinh;
            document.getElementById('ghi_chu').value = ghiChu;
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('updateModal');
            if (event.target == modal) {
                closeUpdateModal();
            }
        }

        // Tự động set quyết định dựa trên tiến độ
        function autoSetQuyetDinh() {
            const tienDoInput = document.getElementById('tien_do');
            const quyetDinhSelect = document.getElementById('quyet_dinh');

            if (tienDoInput && quyetDinhSelect) {
                const tienDo = parseInt(tienDoInput.value) || 0;

                // Nếu < 50%: tự động set "Tạm dừng"
                // Nếu >= 50%: tự động set "Được làm tiếp"
                if (tienDo < 50) {
                    quyetDinhSelect.value = 'tam_dung';
                } else {
                    quyetDinhSelect.value = 'duoc_lam_tiep';
                }
            }
        }

        // Event listener khi người dùng nhập tiến độ
        document.addEventListener('DOMContentLoaded', function() {
            const tienDoInput = document.getElementById('tien_do');
            const updateForm = document.getElementById('updateForm');

            if (tienDoInput) {
                // Tự động cập nhật quyết định khi người dùng nhập tiến độ
                tienDoInput.addEventListener('input', function() {
                    autoSetQuyetDinh();
                });

                // Tự động cập nhật quyết định khi người dùng thay đổi tiến độ
                tienDoInput.addEventListener('change', function() {
                    autoSetQuyetDinh();
                });
            }

            // Đảm bảo tự động set quyết định trước khi submit form
            if (updateForm) {
                updateForm.addEventListener('submit', function(e) {
                    autoSetQuyetDinh();
                });
            }
        });
    </script>
@endsection
