@extends('layouts.app')

@section('title', 'Tạo đề tài mới')

@section('content')
    <div class="page-detai-create">

        <div class="page-header">
            <h1 class="page-title">Tạo đề tài mới</h1>
            <a href="{{ route('phancong-detai.danh-sach') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('phancong-detai.store-detai') }}">
                    @csrf

                    <div class="form-group">
                        <label for="ten_detai">
                            Tên đề tài <span class="required">*</span>
                        </label>
                        <input type="text" id="ten_detai" name="ten_detai"
                            class="form-control @error('ten_detai') is-invalid @enderror" value="{{ old('ten_detai') }}"
                            required>
                        @error('ten_detai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mo_ta">Mô tả đề tài</label>
                        <textarea id="mo_ta" name="mo_ta" rows="5" class="form-control @error('mo_ta') is-invalid @enderror">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="giangvien_id">
                            Giảng viên hướng dẫn <span class="required">*</span>
                        </label>
                        <select id="giangvien_id" name="giangvien_id"
                            class="form-control @error('giangvien_id') is-invalid @enderror" required>
                            <option value="">-- Chọn giảng viên --</option>
                            @foreach ($giangViens as $gv)
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
                        <label>
                            Loại đề tài <span class="required">*</span>
                        </label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="loai" value="ca_nhan"
                                    {{ old('loai', 'ca_nhan') === 'ca_nhan' ? 'checked' : '' }}>
                                <span>Cá nhân</span>
                            </label>

                            <label class="radio-label">
                                <input type="radio" name="loai" value="nhom"
                                    {{ old('loai') === 'nhom' ? 'checked' : '' }}>
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

    </div>
@endsection
