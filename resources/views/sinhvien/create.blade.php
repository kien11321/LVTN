@extends('layouts.app')

@section('title', 'Thêm sinh viên mới')

@section('content')
    <div class="page-sinhvien-create">

        <div class="page-header">
            <h1 class="page-title">Thêm sinh viên mới</h1>
            <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sinhvien.store') }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label>Mã sinh viên <span class="required">*</span></label>
                            <input type="text" name="mssv" class="form-control @error('mssv') is-invalid @enderror"
                                value="{{ old('mssv') }}" placeholder="VD: DH52102319" required>
                            @error('mssv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Họ và tên <span class="required">*</span></label>
                            <input type="text" name="hoten" class="form-control @error('hoten') is-invalid @enderror"
                                value="{{ old('hoten') }}" placeholder="VD: Nguyễn Văn A" required>
                            @error('hoten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="sv@student.edu.vn" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                                value="{{ old('sdt') }}">
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Lớp</label>
                            <input type="text" name="lop" class="form-control @error('lop') is-invalid @enderror"
                                value="{{ old('lop') }}">
                        </div>

                        <div class="form-group">
                            <label>Khoa</label>
                            <input type="text" name="khoa" class="form-control @error('khoa') is-invalid @enderror"
                                value="{{ old('khoa', 'CNTT') }}">
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label>Niên khóa</label>
                            <input type="number" name="nienkhoa"
                                class="form-control @error('nienkhoa') is-invalid @enderror"
                                value="{{ old('nienkhoa', 2021) }}">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary">Thêm sinh viên</button>
                        <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
