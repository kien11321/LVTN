@extends('layouts.app')

@section('title', 'Phân công hướng dẫn')

@section('content')
    <h1 class="page-title">Phân công hướng dẫn</h1>

    @if (session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Thanh tìm kiếm + nút lưu tất cả --}}
    <div
        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <form method="GET" action="{{ route('phancong.index') }}" style="flex:1; min-width:300px; display:flex; gap:5px;">
            <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc MSSV..." value="{{ request('search') }}"
                style="padding:8px; border:1px solid #ddd; border-radius:4px; flex-grow:1;">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
            @if (request('search'))
                <a href="{{ route('phancong.index') }}" class="btn btn-secondary"
                    style="text-decoration:none; padding:8px; background:#eee; color:#333; border-radius:4px;">Xóa lọc</a>
            @endif
        </form>

        {{-- Nút LƯU TẤT CẢ (submit form bảng) --}}
        <button type="submit" form="bulkNhomForm" class="btn btn-primary" style="padding:8px 16px; white-space:nowrap;">
            Lưu
        </button>
    </div>

    {{-- FORM LƯU TẤT CẢ NHÓM --}}
    <form id="bulkNhomForm" method="POST" action="{{ route('phancong.update-nhom-bulk') }}">
        @csrf

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Tên</th>
                        <th>Nhóm</th>
                        <th>Giảng viên hướng dẫn</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>

                <tbody>
                    @if (isset($phanCongs) && $phanCongs->count() > 0)
                        @foreach ($phanCongs as $pc)
                            @php
                                $soNhom = '';
                                if (!empty($pc->nhom) && $pc->nhom !== '-') {
                                    $soNhom = (int) preg_replace('/\D+/', '', $pc->nhom);
                                }
                            @endphp

                            <tr id="sinhvien-{{ $pc->sinhvien_id }}">
                                <td>{{ $pc->mssv }}</td>
                                <td>{{ $pc->hoten }}</td>

                                {{-- Input number nhóm --}}
                                <td>
                                    <input type="number" min="1" name="so_nhom[{{ $pc->sinhvien_id }}]"
                                        value="{{ $soNhom }}" placeholder="VD: 1,2..." class="nhom-number">
                                </td>

                                {{-- Chọn giảng viên: AJAX, không dùng form --}}
                                <td>
                                    <select class="gv-select" data-sinhvien="{{ $pc->sinhvien_id }}">
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach ($giangViens as $gv)
                                            <option value="{{ $gv->id }}"
                                                {{ $pc->giangvien_id == $gv->id ? 'selected' : '' }}>
                                                {{ $gv->hoten }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <span
                                        class="status-badge {{ $pc->trang_thai == 'Đã phân công' ? 'status-assigned' : 'status-pending' }}">
                                        {{ $pc->trang_thai }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#666;">
                                Không có dữ liệu sinh viên
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </form>

    <style>
        .gv-select {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .gv-select:focus {
            outline: none;
            border-color: #007bff;
        }

        .nhom-number {
            width: 100%;
            max-width: 120px;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .nhom-number:focus {
            outline: none;
            border-color: #007bff;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-assigned {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
    </style>

    <script>
        document.querySelectorAll('.gv-select').forEach(sel => {
            sel.addEventListener('change', async () => {
                const sinhvien_id = sel.dataset.sinhvien;
                const giangvien_id = sel.value;

                if (!giangvien_id) return;

                try {
                    const res = await fetch("{{ route('phancong.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            sinhvien_id,
                            giangvien_id
                        })
                    });

                    const data = await res.json().catch(() => null);

                    if (!res.ok) {
                        alert((data && data.message) ? data.message :
                        "Có lỗi khi cập nhật giảng viên!");
                    }
                } catch (e) {
                    alert("Không gọi được server khi cập nhật giảng viên!");
                }
            });
        });
    </script>
@endsection
