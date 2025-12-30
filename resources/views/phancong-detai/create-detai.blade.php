@extends('layouts.app')

@section('title', 'Tạo đề tài mới')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tạo đề tài mới</h1>
    <a href="{{ route('phancong-detai.danh-sach') }}" class="btn btn-secondary">← Quay lại</a>
</div>

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('phancong-detai.store-detai') }}">
            @csrf

            <div class="form-group">
                <label for="ten_detai">Tên đề tài <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="ten_detai" 
                    name="ten_detai" 
                    class="form-control @error('ten_detai') is-invalid @enderror" 
                    value="{{ old('ten_detai') }}"
                    required
                >
                @error('ten_detai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mo_ta">Mô tả đề tài</label>
                <textarea 
                    id="mo_ta" 
                    name="mo_ta" 
                    class="form-control @error('mo_ta') is-invalid @enderror" 
                    rows="5"
                >{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="giangvien_id">Giảng viên hướng dẫn <span class="required">*</span></label>
                <select 
                    id="giangvien_id" 
                    name="giangvien_id" 
                    class="form-control @error('giangvien_id') is-invalid @enderror"
                    required
                >
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($giangViens as $gv)
                        <option value="{{ $gv->id }}" {{ old('giangvien_id') == $gv->id ? 'selected' : '' }}>
                            {{ $gv->hoten }} ({{ $gv->magv }})
                        </option>
                    @endforeach
                </select>
                @error('giangvien_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Loại đề tài <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="loai" value="ca_nhan" {{ old('loai', 'ca_nhan') === 'ca_nhan' ? 'checked' : '' }}>
                        <span>Cá nhân</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="loai" value="nhom" {{ old('loai') === 'nhom' ? 'checked' : '' }}>
                        <span>Nhóm</span>
                    </label>
                </div>
                @error('loai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Tạo đề tài</button>
                <a href="{{ route('phancong-detai.danh-sach') }}" class="btn btn-secondary">Hủy</a>
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
        max-width: 800px;
        margin: 0 auto;
    }

    .card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
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

    .radio-group {
        display: flex;
        gap: 20px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .radio-label input[type="radio"] {
        margin-right: 8px;
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

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }
</style>
@endsection









