@extends('layouts.app')

@section('title', 'Giảng viên Phản biện chấm điểm')

@section('content')
    <div class="page-cham-diem-pb">

        <div class="page-header">
            <div>
                <h1 class="page-title">Giảng viên Phản biện chấm điểm</h1>
                <p class="page-subtitle">Đây là phần chấm điểm luận văn các nhóm dành cho giảng viên phản biện</p>
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
                    <label for="nhom_select" class="label-strong">Chọn Nhóm / Đề tài</label>
                    <select id="nhom_select" name="nhom_id" class="form-control"
                        onchange="window.location.href='{{ route('cham-diem-pb.index') }}?nhom_id=' + this.value">
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
                            $firstDiem = isset($chamDiems[$firstSvId]) ? $chamDiems[$firstSvId]->first() : null;
                        }
                    @endphp

                    <form method="POST" action="{{ route('cham-diem-pb.store') }}" class="score-form">
                        @csrf
                        <input type="hidden" name="detai_id" value="{{ $deTai->id }}">

                        <div class="thang-diem-section">
                            <h3>Thang điểm</h3>
                            <p class="thang-diem-note">
                                Phần này chỉ hiển thị cho giảng viên biết max điểm của từng phần là bao nhiêu
                            </p>
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

                        @foreach ($selectedNhom->sinhViens as $index => $sv)
                            @php
                                $diem = isset($chamDiems[$sv->id]) ? $chamDiems[$sv->id]->first() : null;
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
                                        <label>1. Phân tích <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" name="phan_tich[{{ $sv->id }}]" step="0.1"
                                            min="0" max="2.5" value="{{ number_format($ptThang25, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>2. Thiết kế <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" name="thiet_ke[{{ $sv->id }}]" step="0.1"
                                            min="0" max="2.5" value="{{ number_format($tkThang25, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>3. Hiện thực <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" name="hien_thuc[{{ $sv->id }}]" step="0.1"
                                            min="0" max="2.5" value="{{ number_format($htThang25, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>

                                    <div class="score-item">
                                        <label>4. Báo cáo <span class="max-hint">(max 2.5)</span></label>
                                        <input type="number" name="bao_cao[{{ $sv->id }}]" step="0.1"
                                            min="0" max="2.5" value="{{ number_format($bcThang25, 1) }}"
                                            oninput="recalc('{{ $sv->id }}')">
                                    </div>
                                </div>

                                <div class="total-row">
                                    <div>Tổng cộng (%): <span
                                            id="tong-{{ $sv->id }}">{{ $diem->tong_phan_tram ?? 0 }}</span>%</div>
                                    <div>Điểm chấm: <strong
                                            id="diem-{{ $sv->id }}">{{ $diem->diem_10 ?? 0 }}</strong></div>
                                </div>
                            </div>
                        @endforeach

                        <div class="nhan-xet-section">
                            <h3>Nhận xét chi tiết (Phản biện)</h3>

                            <div class="form-group">
                                <label>Nhận xét tổng quát</label>
                                <textarea name="nhan_xet_tong_quat" rows="3" class="form-control">{{ $firstDiem->nhan_xet_tong_quat ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Thuyết minh:</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="thuyet_minh" value="dat"
                                            {{ ($firstDiem->thuyet_minh ?? 'dat') == 'dat' ? 'checked' : '' }}>
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
                                <label class="bold-label">Đề nghị:</label>
                                <div class="radio-group-horizontal">
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

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Lưu điểm & Nhận xét</button>
                            <a href="{{ route('cham-diem-pb.export-word', $selectedNhom->id) }}" class="btn btn-success">
                                📄 Xuất Phiếu Chấm Điểm (Word)
                            </a>
                        </div>
                    </form>
                @else
                    <div class="text-muted text-center-p40">
                        Vui lòng chọn nhóm để bắt đầu chấm điểm phản biện
                    </div>
                @endif
            </div>
        </div>

        <script>
            function recalc(svId) {
                const root = document.querySelector(`.score-grid[data-sv="${svId}"]`);
                if (!root) return;
                const vals = Array.from(root.querySelectorAll('input')).map(i => parseFloat(i.value || 0));
                const sum25 = vals.reduce((a, b) => a + b, 0);
                const tong = (sum25 / 10) * 100;
                document.getElementById('tong-' + svId).innerText = tong.toFixed(1);
                document.getElementById('diem-' + svId).innerText = (tong * 0.1).toFixed(2);
            }
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.score-grid').forEach(grid => recalc(grid.getAttribute('data-sv')));
            });
        </script>

    </div>
@endsection
