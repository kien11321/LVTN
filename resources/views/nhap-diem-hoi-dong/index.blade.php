@extends('layouts.app')

@section('title', 'Bảng điểm Hội đồng')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-uppercase font-weight-bold">Bảng điểm tổng kết bảo vệ đồ án tốt nghiệp</h5>
                <a href="{{ route('nhap-diem-hoi-dong.export-excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Xuất file Excel
                </a>
            </div>
            <div class="card-body" style="margin-top: 10px;">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="searchInput" class="form-control"
                                style="height:30px; width:450px; border-radius:5px;" placeholder="Tìm kiếm">

                        </div>
                    </div>
                </div>

                <form action="{{ route('nhap-diem-hoi-dong.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="studentTable">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; width: 50px;">STT</th>
                                    <th rowspan="2" style="vertical-align: middle; width: 120px;">MSSV</th>
                                    <th rowspan="2" style="vertical-align: middle; width: 200px;">Họ và tên</th>
                                    <th rowspan="2" style="vertical-align: middle;">Tên đề tài</th>
                                    <th colspan="2">Điểm thành phần (Hệ số)</th>
                                    <th rowspan="2" style="vertical-align: middle; width: 120px;">Tổng điểm</th>
                                </tr>
                                <tr>
                                    <th title="20% HD + 20% PB" style="width: 130px;">Điểm GV (40%)</th>
                                    <th style="width: 150px;">Hội đồng (60%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sinhVienList as $index => $sv)
                                    <tr class="student-row">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center font-weight-bold search-mssv">{{ $sv['mssv'] }}</td>
                                        <td class="search-name">{{ $sv['hoten'] }}</td>
                                        <td>{{ $sv['ten_detai'] }}</td>
                                        <td class="text-center text-primary font-weight-bold">
                                            {{ number_format($sv['diem_gv'], 2) }}
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="10"
                                                name="diem_bao_ve[{{ $sv['detai_id'] }}_{{ $sv['sinhvien_id'] }}]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ $sv['diem_bao_ve'] }}">
                                        </td>
                                        <td class="text-center font-weight-bold text-danger">
                                            {{ number_format($sv['diem_tong'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Chưa có dữ liệu sinh viên được phân hội đồng.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu bảng điểm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('.student-row');

            // Xử lý sự kiện nhập vào ô tìm kiếm
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    // Lấy dữ liệu từ các class đã đặt sẵn
                    const mssv = row.querySelector('.search-mssv').textContent.toLowerCase();
                    const name = row.querySelector('.search-name').textContent.toLowerCase();

                    // Nếu khớp MSSV hoặc Tên thì hiện, ngược lại thì ẩn
                    if (mssv.includes(query) || name.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpush
