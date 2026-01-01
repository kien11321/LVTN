@extends('layouts.app')

@section('title', 'Phân công Giảng viên Phản biện')

@section('content')
    <div class="page-phanbien">

        <div class="page-header">
            <div>
                <h1 class="page-title">Phân công Giảng viên Phản biện</h1>
                <p class="page-subtitle">Giảng viên phản biện không được trùng với giảng viên hướng dẫn.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('phan-bien.export') }}" class="btn btn-success">⬇️ Export ra Excel</a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nhóm</th>
                            <th>Tên Đề tài</th>
                            <th>Thành viên Nhóm</th>
                            <th>Giảng viên Hướng dẫn</th>
                            <th>Giảng viên Phản biện</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($nhoms as $nhom)
                            @php
                                $deTai = $nhom->deTai;
                                $gvhd = $deTai?->giangVien?->hoten;
                                $gvpb = $deTai?->giangVienPhanBien?->hoten;
                            @endphp

                            <tr>
                                <td><strong>{{ $nhom->ten_nhom }}</strong></td>

                                <td>
                                    @if ($deTai)
                                        <div>{{ $deTai->ten_detai }}</div>
                                    @else
                                        <span class="text-muted">Chưa có đề tài</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($nhom->sinhViens->count())
                                        <ul class="member-list-inline">
                                            @foreach ($nhom->sinhViens as $sv)
                                                <li>{{ $sv->hoten }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Chưa có thành viên</span>
                                    @endif
                                </td>

                                <td>{{ $gvhd ?? 'Chưa có' }}</td>

                                <td>
                                    @if ($deTai)
                                        <form method="POST" action="{{ route('phan-bien.update') }}" class="inline-form"
                                            id="form_{{ $deTai->id }}">
                                            @csrf

                                            <input type="hidden" name="detai_id" value="{{ $deTai->id }}">

                                            <select name="gvpb_id" id="gvpb_{{ $deTai->id }}"
                                                class="form-control-inline" required>
                                                <option value="">-- Chọn giảng viên --</option>

                                                @foreach ($giangViens as $gv)
                                                    @if ($gv->id != $deTai->giangvien_id)
                                                        <option value="{{ $gv->id }}"
                                                            {{ $gvpb && $gv->id == $deTai->giangvien_phanbien_id ? 'selected' : '' }}>
                                                            {{ $gv->hoten }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                            <button type="submit" class="btn btn-primary btn-sm btn-assign"
                                                id="btn_{{ $deTai->id }}"
                                                onclick="console.log('Button clicked for detai {{ $deTai->id }}');">
                                                Phân công PB
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Cần phân công đề tài trước</span>
                                    @endif
                                </td>

                                <td class="text-muted">—</td>

                                <td>
                                    @if ($gvpb)
                                        <span class="status-assigned">Đã phân công</span>
                                    @else
                                        <span class="status-pending">Chưa phân công</span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Chưa có nhóm nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.inline-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const select = form.querySelector('select[name="gvpb_id"]');
                        const button = form.querySelector('button[type="submit"]');

                        if (!select.value) {
                            e.preventDefault();
                            alert('Vui lòng chọn giảng viên phản biện!');
                            return false;
                        }

                        if (button) {
                            button.disabled = true;
                            button.textContent = 'Đang lưu...';
                        }

                        console.log('Submitting form:', {
                            detai_id: form.querySelector('input[name="detai_id"]').value,
                            gvpb_id: select.value,
                            action: form.action
                        });

                        return true;
                    });
                });
            });
        </script>

    </div>
@endsection
