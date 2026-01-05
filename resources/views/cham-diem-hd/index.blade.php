@extends('layouts.app')

@section('title', 'Giảng viên Hướng dẫn chấm điểm')

@section('content')
    <div class="page-cham-diem-hd">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Giảng viên Hướng dẫn chấm điểm</h1>
                <p class="page-subtitle">
                    Đây là phần chấm điểm luận văn các nhóm dành cho giảng viên hướng dẫn
                </p>
            </div>
            <div>
                <a href="{{ route('cham-diem-hd.export-excel') }}" class="btn btn-success">
                    📊 Xuất Excel Danh sách SV - GVHD - GVPB
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">

                {{-- CHỌN NHÓM --}}
                <select class="form-control"
                    onchange="window.location.href='{{ route('cham-diem-hd.index') }}?nhom_id=' + this.value">
                    <option value="">-- Chọn nhóm --</option>

                    @foreach ($allNhomSinhViens as $nhom)
                        @php
                            $svNames = $nhom->sinhViens->pluck('hoten')->join(', ');
                            $deTaiTen = $nhom->deTai ? $nhom->deTai->ten_detai : 'Chưa có đề tài';

                            $daCham = in_array($nhom->id, $nhomDaChamIds ?? []);
                        @endphp

                        <option value="{{ $nhom->id }}" {{ request('nhom_id') == $nhom->id ? 'selected' : '' }}>
                            {{ $deTaiTen }} (SV: {{ $svNames }}) - {{ $daCham ? '✅ Đã chấm' : '⏳ Chưa chấm' }}
                        </option>
                    @endforeach
                </select>


                @if ($selectedNhom)

                    {{-- =========================
                    BƯỚC 1: XÁC ĐỊNH ĐÃ CHẤM HẾT CHƯA
                ========================= --}}
                    @php
                        $daChamHet = true;
                        foreach ($selectedNhom->sinhViens as $sv) {
                            if (!isset($chamDiems[$sv->id]) || $chamDiems[$sv->id]->isEmpty()) {
                                $daChamHet = false;
                                break;
                            }
                        }

                        // lấy dữ liệu nhận xét (lấy theo SV đầu tiên)
                        $firstDiem = null;
                        if ($selectedNhom->sinhViens->isNotEmpty()) {
                            $firstSvId = $selectedNhom->sinhViens->first()->id;
                            $firstDiem =
                                isset($chamDiems[$firstSvId]) && $chamDiems[$firstSvId]->isNotEmpty()
                                    ? $chamDiems[$firstSvId]->first()
                                    : null;
                        }
                    @endphp

                    {{-- =========================
                    BƯỚC 2: FORM + DATA ATTR
                ========================= --}}
                    <form method="POST" action="{{ route('cham-diem-hd.store') }}" class="score-form"
                        data-da-cham="{{ $daChamHet ? '1' : '0' }}" onsubmit="return confirmUpdate(this)">
                        @csrf

                        <input type="hidden" name="detai_id" value="{{ $selectedNhom->deTai->id }}">

                        {{-- THANG ĐIỂM --}}
                        <div class="thang-diem-section">
                            <h3>Thang điểm</h3>
                            <p class="thang-diem-note">
                                Chỉ hiển thị cho giảng viên biết điểm tối đa
                            </p>
                            <div class="thang-diem-grid">
                                <div class="thang-diem-item">
                                    <label>Phân tích</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Thiết kế</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Hiện thực</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                                <div class="thang-diem-item">
                                    <label>Báo cáo</label>
                                    <div class="thang-diem-value">2.5</div>
                                </div>
                            </div>
                        </div>

                        {{-- CHẤM ĐIỂM SV --}}
                        @foreach ($selectedNhom->sinhViens as $index => $sv)
                            @php
                                $diem =
                                    isset($chamDiems[$sv->id]) && $chamDiems[$sv->id]->isNotEmpty()
                                        ? $chamDiems[$sv->id]->first()
                                        : null;

                                // hiển thị theo thang 2.5
                                $pt = $diem ? ($diem->phan_tich / 25) * 2.5 : 0;
                                $tk = $diem ? ($diem->thiet_ke / 25) * 2.5 : 0;
                                $ht = $diem ? ($diem->hien_thuc / 25) * 2.5 : 0;
                                $bc = $diem ? ($diem->bao_cao / 25) * 2.5 : 0;
                            @endphp

                            <div class="student-block">
                                <div class="student-head">
                                    <strong>Sinh viên {{ $index + 1 }}</strong>
                                    <span class="text-muted">
                                        MSSV: {{ $sv->mssv }} - {{ $sv->hoten }}
                                    </span>
                                </div>

                                <input type="hidden" name="sinhvien_ids[]" value="{{ $sv->id }}">

                                <div class="score-grid" data-sv="{{ $sv->id }}">
                                    <div class="score-item">
                                        <label>Phân tích <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            name="phan_tich[{{ $sv->id }}]" value="{{ number_format($pt, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>Thiết kế <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            name="thiet_ke[{{ $sv->id }}]" value="{{ number_format($tk, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>Hiện thực <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            name="hien_thuc[{{ $sv->id }}]" value="{{ number_format($ht, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>Báo cáo <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" step="0.1" min="0" max="2.5"
                                            name="bao_cao[{{ $sv->id }}]" value="{{ number_format($bc, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                </div>

                                <div class="total-row">
                                    <div>
                                        Tổng (%):
                                        <span id="tong-{{ $sv->id }}">{{ $diem->tong_phan_tram ?? 0 }}</span>%
                                    </div>
                                    <div>
                                        Điểm:
                                        <strong id="diem-{{ $sv->id }}">{{ $diem->diem_10 ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- NHẬN XÉT CHI TIẾT --}}
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
                                <label>Câu hỏi dành cho sinh viên</label>
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
                                        Không bảo vệ
                                    </label>
                                    <label>
                                        <input type="radio" name="de_nghi" value="bo_sung"
                                            {{ ($firstDiem->de_nghi ?? '') == 'bo_sung' ? 'checked' : '' }}>
                                        Bổ sung
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Lưu điểm</button>
                            <a href="{{ route('cham-diem-hd.export-word', $selectedNhom->id) }}" class="btn btn-success">
                                📄 Xuất Phiếu
                            </a>
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

    {{-- =========================
    SCRIPT
========================= --}}
    <script>
        function confirmUpdate(form) {
            if (form.getAttribute('data-da-cham') === '1') {
                return confirm('⚠️ Nhóm này đã được chấm điểm trước đó.\nBạn có chắc chắn muốn cập nhật lại điểm không?');
            }
            return true;
        }

        function recalc(svId) {
            const grid = document.querySelector(`.score-grid[data-sv="${svId}"]`);
            if (!grid) return;

            const getVal = (name) => {
                const el = grid.querySelector(`input[name="${name}[${svId}]"]`);
                return el ? parseFloat(el.value) || 0 : 0;
            };

            // total theo thang 2.5 => max 10
            const total = getVal('phan_tich') + getVal('thiet_ke') + getVal('hien_thuc') + getVal('bao_cao');

            // % (max 100)
            const percent = (total / 10) * 100;

            // điểm thang 10 = percent * 0.1 = total (vì total max 10)
            const diem10 = total;

            const tongEl = document.getElementById(`tong-${svId}`);
            const diemEl = document.getElementById(`diem-${svId}`);

            if (tongEl) tongEl.innerText = percent.toFixed(1);
            if (diemEl) diemEl.innerText = diem10.toFixed(1);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.score-grid').forEach(grid => {
                recalc(grid.getAttribute('data-sv'));
            });
        });
    </script>
@endsection
