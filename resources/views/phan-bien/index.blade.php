@extends('layouts.app')

@section('title', 'Phân công Giảng viên Phản biện')

@section('content')
    <div class="page-phanbien">

        <div class="page-header">
            <div>
                <h1 class="page-title">Phân công Giảng viên Phản biện</h1>
                <p class="page-subtitle">Giảng viên phản biện không được trùng với giảng viên hướng dẫn.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('phan-bien.export') }}" class="btn btn-success">⬇️ Export ra Excel</a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo MSSV hoặc họ tên...">

        <div class="card">
            <div class="table-container">
                <table id="scoreTable">
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
                                    @if ($deTai)
                                        <div>{{ $deTai->ten_detai }}</div>
                                    @else
                                        <span class="text-muted">Chưa có đề tài</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($nhom->sinhViens->count())
                                        <ul class="member-list-inline">
                                            @foreach ($nhom->sinhViens as $sv)
                                                <li>{{ $sv->hoten }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Chưa có thành viên</span>
                                    @endif
                                </td>

                                <td>{{ $gvhd ?? 'Chưa có' }}</td>

                                <td>
                                    @if ($deTai)
                                        <form method="POST" action="{{ route('phan-bien.update') }}" class="inline-form"
                                            id="form_{{ $deTai->id }}">
                                            @csrf

                                            <input type="hidden" name="detai_id" value="{{ $deTai->id }}">

                                            <select name="gvpb_id" id="gvpb_{{ $deTai->id }}"
                                                class="form-control-inline" required>
                                                <option value="">-- Chọn giảng viên --</option>

                                                @foreach ($giangViens as $gv)
                                                    @if ($gv->id != $deTai->giangvien_id)
                                                        <option value="{{ $gv->id }}"
                                                            {{ $gvpb && $gv->id == $deTai->giangvien_phanbien_id ? 'selected' : '' }}>
                                                            {{ $gv->hoten }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                            <button type="submit" class="btn btn-primary btn-sm btn-assign"
                                                id="btn_{{ $deTai->id }}"
                                                onclick="console.log('Button clicked for detai {{ $deTai->id }}');">
                                                Phân công PB
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Cần phân công đề tài trước</span>
                                    @endif
                                </td>

                                <td class="text-muted">—</td>

                                <td>
                                    @if ($gvpb)
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* =========================
                 * SUBMIT FORM
                 *
                 * ========================= */
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

                        if (button) {
                            button.disabled = true;
                            button.textContent = 'Đang lưu...';
                        }

                        console.log('Submitting form:', {
                            detai_id: form.querySelector('input[name="detai_id"]').value,
                            gvpb_id: select.value,
                            action: form.action
                        });

                        return true;
                    });
                });

                /* =========================
                 * TÌM KIẾM 
                 * ========================= */
                const input = document.getElementById('searchInput');
                const table = document.getElementById('scoreTable');
                if (!input || !table) return;

                const rows = Array.from(table.querySelectorAll('tbody tr'));

                function normalize(str) {
                    return (str || '')
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
                        .trim();
                }

                input.addEventListener('input', function() {
                    const q = normalize(input.value);

                    rows.forEach(row => {
                        // Cột theo bảng hiện tại
                        const tenNhom = normalize(row.cells[0]?.innerText);
                        const tenDeTai = normalize(row.cells[1]?.innerText);
                        const thanhVien = normalize(row.cells[2]?.innerText);
                        const gvhd = normalize(row.cells[3]?.innerText);

                        // Lấy GVPB đang được chọn (nếu có)
                        const select = row.querySelector('select[name="gvpb_id"]');
                        const gvpbSelected = select ?
                            normalize(select.options[select.selectedIndex]?.text) :
                            '';

                        const haystack = `${tenNhom} ${tenDeTai} ${thanhVien} ${gvhd} ${gvpbSelected}`;
                        const match = !q || haystack.includes(q);

                        row.style.display = match ? '' : 'none';
                    });
                });

            });
        </script>


    </div>
@endsection
