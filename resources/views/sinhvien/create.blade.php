@extends('layouts.app')

@section('title', 'Thêm sinh viên mới')

@section('content')
<div class="page-header">
    <h1 class="page-title">Thêm sinh viên mới</h1>
    <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">← Quay lại</a>
</div>

@if(session('error'))
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
                    <label for="mssv">Mã sinh viên <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="mssv" 
                        name="mssv" 
                        class="form-control @error('mssv') is-invalid @enderror" 
                        value="{{ old('mssv') }}"
                        placeholder="VD: DH52102319"
                        required
                    >
                    @error('mssv')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hoten">Họ và tên <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="hoten" 
                        name="hoten" 
                        class="form-control @error('hoten') is-invalid @enderror" 
                        value="{{ old('hoten') }}"
                        placeholder="VD: Nguyễn Văn A"
                        required
                    >
                    @error('hoten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}"
                        placeholder="VD: dh52102319@student.stu.edu.vn"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sdt">Số điện thoại</label>
                    <input 
                        type="text" 
                        id="sdt" 
                        name="sdt" 
                        class="form-control @error('sdt') is-invalid @enderror" 
                        value="{{ old('sdt') }}"
                        placeholder="VD: 0901234567"
                    >
                    @error('sdt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lop">Lớp</label>
                    <input 
                        type="text" 
                        id="lop" 
                        name="lop" 
                        class="form-control @error('lop') is-invalid @enderror" 
                        value="{{ old('lop') }}"
                        placeholder="VD: D21_TH01"
                    >
                    @error('lop')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="khoa">Khoa</label>
                    <input 
                        type="text" 
                        id="khoa" 
                        name="khoa" 
                        class="form-control @error('khoa') is-invalid @enderror" 
                        value="{{ old('khoa', 'CNTT') }}"
                        placeholder="VD: CNTT"
                    >
                    @error('khoa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nienkhoa">Niên khóa</label>
                    <input 
                        type="number" 
                        id="nienkhoa" 
                        name="nienkhoa" 
                        class="form-control @error('nienkhoa') is-invalid @enderror" 
                        value="{{ old('nienkhoa', 2021) }}"
                        placeholder="VD: 2021"
                    >
                    @error('nienkhoa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
                <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-width: 900px;
        margin: 0 auto;
    }

    .card-body {
        padding: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .alert {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endsection









