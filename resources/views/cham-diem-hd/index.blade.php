@extends('layouts.app')

@section('title', 'Giảng viên Hướng dẫn chấm điểm')

@section('content')
    <div class="page-cham-diem-hd">

        <div class="page-header">
            <div>
                <h1 class="page-title">Giảng viên Hướng dẫn chấm điểm</h1>
                <p class="page-subtitle">
                    Đây là phần chấm điểm luận văn các nhóm dành cho giảng viên hướng dẫn
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">

                <div class="form-group mb-20">
                    <label class="label-strong" for="nhom_select">Chọn Nhóm / Đề tài</label>
                    <select id="nhom_select" name="nhom_id" class="form-control"
                        onchange="window.location.href='{{ route('cham-diem-hd.index') }}?nhom_id=' + this.value">
                        <option value="">-- Chọn nhóm --</option>
                        @foreach ($allNhomSinhViens as $nhom)
                            @php
                                $svNames = $nhom->sinhViens->pluck('hoten')->join(', ');
                                $deTaiTen = $nhom->deTai ? $nhom->deTai->ten_detai : 'Chưa có đề tài';
                            @endphp
                            <option value="{{ $nhom->id }}" {{ request('nhom_id') == $nhom->id ? 'selected' : '' }}>
                                {{ $deTaiTen }} (SV: {{ $svNames }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($selectedNhom)

                    <form method="POST" action="{{ route('cham-diem-hd.store') }}">
                        @csrf

                        <div class="thang-diem-section">
                            <h3>Thang điểm</h3>
                            <p class="thang-diem-note">
                                Phần này chỉ hiển thị cho giảng viên biết max điểm của từng phần là bao nhiêu
                            </p>

                            <div class="thang-diem-grid">
                                <div class="thang-diem-item">
                                    <label>Phân tích vấn đề</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Thiết kế vấn đề</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Hiện thực vấn đề</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Báo cáo/Tài liệu</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                            </div>
                        </div>

                        @foreach ($selectedNhom->sinhViens as $index => $sv)
                            <div class="student-block">

                                <div class="student-head">
                                    <strong>Sinh viên {{ $index + 1 }}</strong>
                                    <span class="text-muted">
                                        MSSV: {{ $sv->mssv }} - {{ $sv->hoten }}
                                    </span>
                                </div>

                                <div class="score-grid" data-sv="{{ $sv->id }}">
                                    <div class="score-item">
                                        <label>Phân tích <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                    <div class="score-item">
                                        <label>Thiết kế <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                    <div class="score-item">
                                        <label>Hiện thực <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                    <div class="score-item">
                                        <label>Báo cáo <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                </div>

                                <div class="total-row">
                                    <div>Tổng (%): <span id="tong-{{ $sv->id }}">0</span>%</div>
                                    <div>Điểm: <strong id="diem-{{ $sv->id }}">0</strong></div>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-actions">
                            <button class="btn btn-primary">Lưu điểm</button>
                        </div>

                    </form>
                @else
                    <div class="text-muted text-center-p40">
                        Vui lòng chọn nhóm để bắt đầu chấm điểm
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
