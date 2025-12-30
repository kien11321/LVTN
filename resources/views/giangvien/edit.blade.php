@extends('layouts.app')

@section('title', 'Sửa giảng viên')

@section('content')
    <h1 class="page-title">Sửa thông tin giảng viên</h1>

    <div class="table-container" style="max-width: 600px;">
        <form method="POST" action="{{ route('giangvien.update', $giangVien->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="magv">Mã giảng viên *</label>
                <input type="text" id="magv" name="magv" class="form-control" value="{{ old('magv', $giangVien->magv) }}" required>
                @error('magv')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hoten">Họ tên *</label>
                <input type="text" id="hoten" name="hoten" class="form-control" value="{{ old('hoten', $giangVien->hoten) }}" required>
                @error('hoten')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $giangVien->email) }}">
                @error('email')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" class="form-control" value="{{ old('sdt', $giangVien->sdt) }}">
                @error('sdt')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="bo_mon">Bộ môn</label>
                <input type="text" id="bo_mon" name="bo_mon" class="form-control" value="{{ old('bo_mon', $giangVien->bo_mon) }}">
                @error('bo_mon')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('giangvien.index') }}" class="btn" style="background: #6c757d; color: white; text-decoration: none; padding: 8px 20px; border-radius: 4px;">Hủy</a>
            </div>
        </form>
    </div>

    <style>
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }
    </style>
@endsection














