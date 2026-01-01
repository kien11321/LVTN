@extends('layouts.app')

@section('title', 'Phân công hướng dẫn')

@section('content')
    <div class="page-phancong-huongdan">

        <h1 class="page-title">Phân công hướng dẫn</h1>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Thanh tìm kiếm + nút lưu --}}
        <div class="toolbar">
            <form method="GET" action="{{ route('phancong.index') }}" class="search-form">
                <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc MSSV..."
                    value="{{ request('search') }}" class="search-input">

                <button type="submit" class="btn btn-success">Tìm kiếm</button>

                @if (request('search'))
                    <a href="{{ route('phancong.index') }}" class="btn btn-secondary">Xóa lọc</a>
                @endif
            </form>

            <button type="submit" form="bulkNhomForm" class="btn btn-primary btn-save-all">
                Lưu
            </button>
        </div>

        {{-- FORM LƯU TẤT CẢ --}}
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
                        @if ($phanCongs->count())
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

                                    <td>
                                        <input type="number" min="1" name="so_nhom[{{ $pc->sinhvien_id }}]"
                                            value="{{ $soNhom }}" placeholder="VD: 1,2..." class="nhom-number">
                                    </td>

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
                                            class="status-badge
                                        {{ $pc->trang_thai == 'Đã phân công' ? 'status-assigned' : 'status-pending' }}">
                                            {{ $pc->trang_thai }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Không có dữ liệu sinh viên
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </form>

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
                            alert(data?.message || "Có lỗi khi cập nhật giảng viên!");
                        }
                    } catch {
                        alert("Không gọi được server!");
                    }
                });
            });
        </script>

    </div>
@endsection
