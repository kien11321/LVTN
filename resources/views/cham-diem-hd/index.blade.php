@extends('layouts.app')

@section('title', 'Giảng viên Hướng dẫn chấm điểm')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Giảng viên Hướng dẫn chấm điểm</h1>
            <p class="page-subtitle">Đây là phần chấm điểm luận văn các nhóm dành cho giảng viên hướng dẫn</p>
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
            <!-- Dropdown chọn nhóm/đề tài -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="nhom_select" style="font-weight: 600; margin-bottom: 8px; display: block;">Chọn Nhóm / Đề
                    tài</label>
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
                @php
                    $deTai = $selectedNhom->deTai;
                    $firstDiem = null;
                    if ($selectedNhom->sinhViens->isNotEmpty()) {
                        $firstSvId = $selectedNhom->sinhViens->first()->id;
                        $firstDiem =
                            isset($chamDiems[$firstSvId]) && $chamDiems[$firstSvId]->isNotEmpty()
                                ? $chamDiems[$firstSvId]->first()
                                : null;
                    }
                @endphp

                <form method="POST" action="{{ route('cham-diem-hd.store') }}" class="score-form">
                    @csrf
                    <input type="hidden" name="detai_id" value="{{ $deTai->id }}">

                    <!-- Phần Thang điểm (chỉ hiển thị) -->
                    <div class="thang-diem-section">
                        <h3>Thang điểm</h3>
                        <p class="thang-diem-note">Phần này chỉ hiển thị cho giảng viên biết max điểm của từng phần là bao
                            nhiêu</p>
                        <div class="thang-diem-grid">
                            <div class="thang-diem-item">
                                <label>1. Phân tích vấn đề</label>
                                <div class="thang-diem-value">2.5</div>
                            </div>
                            <div class="thang-diem-item">
                                <label>2. Thiết kế vấn đề</label>
                                <div class="thang-diem-value">2.5</div>
                            </div>
                            <div class="thang-diem-item">
                                <label>3. Hiện thực vấn đề</label>
                                <div class="thang-diem-value">2.5</div>
                            </div>
                            <div class="thang-diem-item">
                                <label>4. Báo cáo/Tài liệu</label>
                                <div class="thang-diem-value">2.5</div>
                            </div>
                        </div>
                    </div>

                    <!-- Nhập điểm cho từng sinh viên -->
                    @foreach ($selectedNhom->sinhViens as $index => $sv)
                        @php
                            $diem =
                                isset($chamDiems[$sv->id]) && $chamDiems[$sv->id]->isNotEmpty()
                                    ? $chamDiems[$sv->id]->first()
                                    : null;
                            // Chuyển từ % (0-25) sang thang 2.5 để hiển thị
                            $ptThang25 = $diem ? ($diem->phan_tich / 25) * 2.5 : 0;
                            $tkThang25 = $diem ? ($diem->thiet_ke / 25) * 2.5 : 0;
                            $htThang25 = $diem ? ($diem->hien_thuc / 25) * 2.5 : 0;
                            $bcThang25 = $diem ? ($diem->bao_cao / 25) * 2.5 : 0;
                        @endphp
                        <div class="student-block">
                            <div class="student-head">
                                <strong>Sinh viên {{ $index + 1 }}{{ $index > 0 ? ' (Nếu có)' : '' }}</strong>
                                <span class="text-muted">MSSV: {{ $sv->mssv }} - {{ $sv->hoten }}</span>
                            </div>
                            <input type="hidden" name="sinhvien_ids[]" value="{{ $sv->id }}">

                            <div class="score-grid" data-sv="{{ $sv->id }}">
                                <div class="score-item">
                                    <label>1. Phân tích vấn đề <span class="max-hint">(max 2.5)</span></label>
                                    <input type="number" name="phan_tich[{{ $sv->id }}]" step="0.1"
                                        min="0" max="2.5" value="{{ number_format($ptThang25, 1) }}"
                                        oninput="recalc('{{ $sv->id }}')">
                                </div>
                                <div class="score-item">
                                    <label>2. Thiết kế vấn đề <span class="max-hint">(max 2.5)</span></label>
                                    <input type="number" name="thiet_ke[{{ $sv->id }}]" step="0.1"
                                        min="0" max="2.5" value="{{ number_format($tkThang25, 1) }}"
                                        oninput="recalc('{{ $sv->id }}')">
                                </div>
                                <div class="score-item">
                                    <label>3. Hiện thực vấn đề <span class="max-hint">(max 2.5)</span></label>
                                    <input type="number" name="hien_thuc[{{ $sv->id }}]" step="0.1"
                                        min="0" max="2.5" value="{{ number_format($htThang25, 1) }}"
                                        oninput="recalc('{{ $sv->id }}')">
                                </div>
                                <div class="score-item">
                                    <label>4. Báo cáo/Tài liệu <span class="max-hint">(max 2.5)</span></label>
                                    <input type="number" name="bao_cao[{{ $sv->id }}]" step="0.1" min="0"
                                        max="2.5" value="{{ number_format($bcThang25, 1) }}"
                                        oninput="recalc('{{ $sv->id }}')">
                                </div>
                            </div>

                            <div class="total-row">
                                <div>Tổng cộng (%): <span
                                        id="tong-{{ $sv->id }}">{{ $diem->tong_phan_tram ?? 0 }}</span>%</div>
                                <div>Điểm chấm: <strong id="diem-{{ $sv->id }}">{{ $diem->diem_10 ?? 0 }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Nhận xét chi tiết -->
                    <div class="nhan-xet-section">
                        <h3>Nhận xét chi tiết</h3>

                        <div class="form-group">
                            <label>Nhận xét tổng quát</label>
                            <textarea name="nhan_xet_tong_quat" rows="4" class="form-control">{{ $firstDiem->nhan_xet_tong_quat ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Thuyết minh:</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="thuyet_minh" value="dat"
                                        {{ ($firstDiem->thuyet_minh ?? '') == 'dat' ? 'checked' : '' }}>
                                    Đạt
                                </label>
                                <label>
                                    <input type="radio" name="thuyet_minh" value="khong_dat"
                                        {{ ($firstDiem->thuyet_minh ?? '') == 'khong_dat' ? 'checked' : '' }}>
                                    Không đạt
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ưu điểm</label>
                            <textarea name="uu_diem" rows="4" class="form-control">{{ $firstDiem->uu_diem ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Thiếu sót</label>
                            <textarea name="thieu_sot" rows="4" class="form-control">{{ $firstDiem->thieu_sot ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Câu hỏi</label>
                            <textarea name="cau_hoi" rows="4" class="form-control">{{ $firstDiem->cau_hoi ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Đề nghị:</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="de_nghi" value="duoc_bao_ve"
                                        {{ ($firstDiem->de_nghi ?? '') == 'duoc_bao_ve' ? 'checked' : '' }}>
                                    Được bảo vệ
                                </label>
                                <label>
                                    <input type="radio" name="de_nghi" value="khong_bao_ve"
                                        {{ ($firstDiem->de_nghi ?? '') == 'khong_bao_ve' ? 'checked' : '' }}>
                                    Không Bảo Vệ
                                </label>
                                <label>
                                    <input type="radio" name="de_nghi" value="bo_sung"
                                        {{ ($firstDiem->de_nghi ?? '') == 'bo_sung' ? 'checked' : '' }}>
                                    Bổ sung
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Lưu điểm</button>
                        <a href="{{ route('cham-diem-hd.export-word', $selectedNhom->id) }}" class="btn btn-success">
                            📄 Xuất Phiếu Chấm Điểm
                        </a>
                    </div>
                </form>
            @else
                <div class="text-muted" style="text-align: center; padding: 40px;">
                    Vui lòng chọn nhóm để bắt đầu chấm điểm
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .page-subtitle {
            margin: 4px 0 0;
            color: #666;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .thang-diem-section {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .thang-diem-section h3 {
            margin-top: 0;
            color: #dc2626;
        }

        .thang-diem-note {
            font-size: 12px;
            color: #991b1b;
            margin-bottom: 12px;
            font-style: italic;
        }

        .thang-diem-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .thang-diem-item {
            background: #fff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #fecaca;
        }

        .thang-diem-item label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #7f1d1d;
        }

        .thang-diem-value {
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
            text-align: center;
        }

        .student-block {
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .student-head {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .student-head strong {
            font-size: 16px;
            color: #1f2937;
        }

        .score-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .score-item label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }

        .score-item input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .score-item input:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .max-hint {
            color: #6b7280;
            font-weight: 400;
            font-size: 12px;
        }

        .total-row {
            display: flex;
            gap: 24px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-weight: 600;
        }

        .total-row div {
            color: #1f2937;
        }

        .total-row strong {
            color: #059669;
            font-size: 18px;
        }

        .nhan-xet-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #e5e7eb;
        }

        .nhan-xet-section h3 {
            margin-bottom: 20px;
            color: #1f2937;
        }

        .radio-group {
            display: flex;
            gap: 24px;
            margin-top: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 400;
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .form-actions {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .btn-primary {
            background: #4f46e5;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-success {
            background: #10b981;
        }

        .btn-success:hover {
            background: #059669;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .text-muted {
            color: #6b7280;
        }
    </style>

    <script>
        function recalc(svId) {
            const root = document.querySelector(`.score-grid[data-sv="${svId}"]`);
            if (!root) return;

            // Lấy giá trị nhập vào (thang 2.5)
            const vals = Array.from(root.querySelectorAll('input')).map(i => parseFloat(i.value || 0));
            const sumThang25 = vals.reduce((a, b) => a + b, 0);

            // Chuyển sang %: tổng thang 2.5 (max 10) -> % (max 100)
            const tong = (sumThang25 / 10) * 100;
            const capped = Math.min(tong, 100);

            // Điểm chấm = tổng % x 0.1
            const diem = (capped * 0.1).toFixed(2);

            document.getElementById('tong-' + svId).innerText = capped.toFixed(1);
            document.getElementById('diem-' + svId).innerText = diem;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.score-grid').forEach(grid => {
                recalc(grid.getAttribute('data-sv'));
            });
        });
    </script>
@endsection
