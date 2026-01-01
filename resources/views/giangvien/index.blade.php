@extends('layouts.app')

@section('title', 'Quản lý giảng viên')

@section('content')
<div class="page-giangvien-index">

    <div class="page-topbar">
        <h1 class="page-title">Quản lý giảng viên</h1>

        <div class="topbar-actions">
            <div class="file-input-wrapper">
                <input type="file" id="fileInputGV" accept=".xlsx,.xls" class="hidden-file-input">

                <label for="fileInputGV" class="btn btn-secondary btn-sm">Chọn file</label>
                <span id="fileNameGV" class="file-name">Chưa chọn file</span>
            </div>

            <a href="{{ route('giangvien.create') }}" class="btn btn-primary">Thêm giảng viên</a>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('giangvien.index') }}" class="search-bar">
        <input type="text" class="search-input" name="search" placeholder="Tìm kiếm"
               value="{{ $search ?? '' }}" id="searchInputGV">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
    </form>

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Lecturer Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã giảng viên</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SDT</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @if (isset($giangViens) && $giangViens->count() > 0)
                    @foreach ($giangViens as $index => $gv)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $gv->magv ?? '-' }}</td>
                            <td>{{ $gv->hoten ?? '-' }}</td>
                            <td>{{ $gv->email ?: '-' }}</td>
                            <td>{{ $gv->sdt ?: '-' }}</td>
                            <td>
                                <span class="badge-role">
                                    {{ $gv->vaitro ?? 'huongdan' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('giangvien.edit', $gv->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                                    <form method="POST" action="{{ route('giangvien.destroy', $gv->id) }}"
                                          class="inline-form"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa giảng viên này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="empty-cell">
                            Không tìm thấy giảng viên nào.
                            <br>
                            <a href="{{ route('giangvien.create') }}" class="empty-link">Thêm giảng viên đầu tiên</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if (session('success'))
        <div id="toastSuccessGV" class="toast-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                const el = document.getElementById('toastSuccessGV');
                if (el) el.remove();
            }, 3000);
        </script>
    @endif

    <script>
        // File input handler
        document.getElementById('fileInputGV').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Chưa chọn file';
            document.getElementById('fileNameGV').textContent = fileName;
        });
    </script>

</div>
@endsection
