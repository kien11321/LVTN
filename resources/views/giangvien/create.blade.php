@extends('layouts.app')

@section('title', 'Thêm giảng viên')

@section('content')
    <h1 class="page-title">Thêm giảng viên mới</h1>

    <div class="table-container" style="max-width: 600px;">
        <form method="POST" action="{{ route('giangvien.store') }}">
            @csrf

            <div class="form-group">
                <label for="magv">Mã giảng viên *</label>
                <input type="text" id="magv" name="magv" class="form-control" value="{{ old('magv') }}" required>
                @error('magv')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hoten">Họ tên *</label>
                <input type="text" id="hoten" name="hoten" class="form-control" value="{{ old('hoten') }}" required>
                @error('hoten')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" class="form-control" value="{{ old('sdt') }}">
                @error('sdt')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="bo_mon">Bộ môn</label>
                <input type="text" id="bo_mon" name="bo_mon" class="form-control" value="{{ old('bo_mon') }}">
                @error('bo_mon')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('giangvien.index') }}" class="btn" style="background: #6c757d; color: white; text-decoration: none; padding: 8px 20px; border-radius: 4px;">Hủy</a>
            </div>
        </form>
    </div>

<style>
    /* Tiêu đề */
    .page-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    /* Khung form */
    .table-container {
        background: #ffffff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    /* Nhóm input */
    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #444;
    }

    /* Input */
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.15);
    }

    /* Thông báo lỗi */
    .form-group div {
        font-size: 13px;
    }

    /* Nút */
    .btn {
        padding: 8px 18px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: opacity 0.2s, transform 0.1s;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .btn:active {
        transform: scale(0.97);
    }

    /* Link hủy */
    a.btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

@endsection














