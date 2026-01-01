@extends('layouts.app')

@section('title', 'Thêm giảng viên')

@section('content')
    <div class="page-giangvien-create">

        <h1 class="page-title">Thêm giảng viên mới</h1>

        <div class="table-container table-container--mw600">
            <form method="POST" action="{{ route('giangvien.store') }}">
                @csrf

                <div class="form-group">
                    <label for="magv">Mã giảng viên *</label>
                    <input type="text" id="magv" name="magv" class="form-control" value="{{ old('magv') }}"
                        required>
                    @error('magv')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hoten">Họ tên *</label>
                    <input type="text" id="hoten" name="hoten" class="form-control" value="{{ old('hoten') }}"
                        required>
                    @error('hoten')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sdt">Số điện thoại</label>
                    <input type="text" id="sdt" name="sdt" class="form-control" value="{{ old('sdt') }}">
                    @error('sdt')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bo_mon">Bộ môn</label>
                    <input type="text" id="bo_mon" name="bo_mon" class="form-control" value="{{ old('bo_mon') }}">
                    @error('bo_mon')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Lưu</button>
                    <a href="{{ route('giangvien.index') }}" class="btn btn-cancel">Hủy</a>
                </div>
            </form>
        </div>

    </div>
@endsection
