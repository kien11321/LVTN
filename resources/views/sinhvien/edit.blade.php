@extends('layouts.app')

@section('title', 'Sửa thông tin sinh viên')

@section('content')
    <div class="page-sinhvien-edit">

        <div class="page-header">
            <h1 class="page-title">Sửa thông tin sinh viên</h1>
            <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sinhvien.update', $sinhVien->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label>Mã sinh viên <span class="required">*</span></label>
                            <input type="text" name="mssv" class="form-control @error('mssv') is-invalid @enderror"
                                value="{{ old('mssv', $sinhVien->mssv) }}" required>
                            @error('mssv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Họ và tên <span class="required">*</span></label>
                            <input type="text" name="hoten" class="form-control @error('hoten') is-invalid @enderror"
                                value="{{ old('hoten', $sinhVien->hoten) }}" required>
                            @error('hoten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $sinhVien->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                                value="{{ old('sdt', $sinhVien->sdt) }}">
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Lớp</label>
                            <input type="text" name="lop" class="form-control @error('lop') is-invalid @enderror"
                                value="{{ old('lop', $sinhVien->lop) }}">
                            @error('lop')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Khoa</label>
                            <input type="text" name="khoa" class="form-control @error('khoa') is-invalid @enderror"
                                value="{{ old('khoa', $sinhVien->khoa) }}">
                            @error('khoa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label>Niên khóa</label>
                            <input type="number" name="nienkhoa"
                                class="form-control @error('nienkhoa') is-invalid @enderror"
                                value="{{ old('nienkhoa', $sinhVien->nienkhoa) }}">
                            @error('nienkhoa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection
