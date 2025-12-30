@extends('layouts.app')

@section('title', 'Quản lý giảng viên')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title" style="margin: 0;">Quản lý giảng viên</h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="file-input-wrapper">
                <input type="file" id="fileInputGV" accept=".xlsx,.xls" style="display: none;">
                
            </div>
            
            <a href="{{ route('giangvien.create') }}" class="btn btn-primary">Thêm giảng viên</a>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('giangvien.index') }}" class="search-bar">
        <input type="text" class="search-input" name="search" placeholder="Tìm kiếm" value="{{ $search ?? '' }}"
            id="searchInputGV">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
    </form>

    @if (session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
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
                                <span
                                    style="display: inline-block; padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    {{ $gv->vaitro ?? 'huongdan' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('giangvien.edit', $gv->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form method="POST" action="{{ route('giangvien.destroy', $gv->id) }}"
                                        style="display: inline;"
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
                        <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                            Không tìm thấy giảng viên nào.
                            <br><a href="{{ route('giangvien.create') }}"
                                style="color: #007bff; text-decoration: underline;">Thêm giảng viên đầu tiên</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if (session('success'))
        <div
            style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 20px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 1000;">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('[style*="position: fixed"]').remove();
            }, 3000);
        </script>
    @endif

    <script>
        // File input handler
        document.getElementById('fileInputGV').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Không tệp nào được chọn';
            document.getElementById('fileNameGV').textContent = fileName;
        });
    </script>
@endsection
