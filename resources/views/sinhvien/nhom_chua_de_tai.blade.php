@extends('layouts.app')

@section('title', 'Sinh viên chưa có đề tài')

@section('content')
<div class="page-sinhvien-chua-detai">

    <div class="page-header">
        <div>
            <h1 class="page-title">Sinh viên chưa có đề tài</h1>
            <p class="page-subtitle">- Danh sách thống kê sinh viên chưa đăng ký đề tài LVTN</p>
        </div>
    </div>

    <div class="search-container">
        <form method="GET" action="{{ route('sinhvien.nhom-chua-de-tai') }}" class="search-form">
            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Tìm kiếm MSSV hoặc tên sinh viên..."
                value="{{ $search }}"
            >
            <button type="submit" class="btn-search">Tìm kiếm</button>

            @if ($search)
                <a href="{{ route('sinhvien.nhom-chua-de-tai') }}" class="btn-reset">Hủy lọc</a>
            @endif
        </form>
    </div>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width:60px">STT</th>
                        <th style="width:150px">Mã SV</th>
                        <th style="width:350px">Họ tên</th>
                        <th style="width:120px">Lớp</th>
                        <th style="width:350px">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sinhViens as $index => $sv)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="mssv-column">{{ $sv->mssv }}</td>
                            <td class="name-column">{{ $sv->hoten }}</td>
                            <td>{{ $sv->lop }}</td>
                            <td>{{ $sv->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                Không có sinh viên nào chưa có đề tài.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
