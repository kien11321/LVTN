@extends('layouts.app')

@section('title', 'Phân công đề tài cho nhóm')

@section('content')
    <div class="page-phancong-detai-nhom">

        <div class="page-header">
            <h1 class="page-title">Phân công đề tài cho nhóm</h1>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form phân công -->
        <div class="card mb-30">
            <div class="card-header">
                <h3>Phân công / Sửa đề tài</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('phancong-detai.store-simple') }}">
                    @csrf
                    <div class="form-row-inline">
                        <div class="form-group-inline">
                            <label>Chọn nhóm</label>
                            <select id="nhom_id" name="nhom_id" class="form-control-inline" required
                                onchange="showCurrentDetai(this)">
                                <option value="">-- Chọn nhóm --</option>
                                @foreach ($nhomSinhViens as $nhom)
                                    <option value="{{ $nhom->id }}"
                                        data-current-detai="{{ $nhomDeTaiMap[$nhom->id] ?? '' }}">
                                        {{ $nhom->ten_nhom }} ({{ $nhom->sinhViens->count() }} SV)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-inline flex-grow">
                            <label>Tên đề tài</label>
                            <input type="text" id="ten_detai" name="ten_detai" class="form-control-inline"
                                placeholder="Nhập tên đề tài..." required>
                            <small id="current-detai-hint" class="text-muted"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách đã phân -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Nhóm</th>
                        <th>Đề tài</th>
                        <th>Thành viên</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allNhomSinhViens as $i => $nhom)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $nhom->ten_nhom }}</strong></td>
                            <td>
                                <strong>{{ $nhom->deTai->ten_detai ?? 'Chưa có đề tài' }}</strong>
                                @if ($nhom->deTai?->giangVien)
                                    <br><small class="text-muted">GV: {{ $nhom->deTai->giangVien->hoten }}</small>
                                @endif
                            </td>
                            <td>
                                <ul class="member-list-inline">
                                    @foreach ($nhom->sinhViens as $sv)
                                        <li>{{ $sv->hoten }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function showCurrentDetai(select) {
            const option = select.options[select.selectedIndex];
            const hint = document.getElementById('current-detai-hint');
            const input = document.getElementById('ten_detai');

            const detai = option.dataset.currentDetai;
            if (detai) {
                hint.textContent = 'Đề tài hiện tại: ' + detai + ' (có thể sửa)';
                hint.style.display = 'block';
                input.value = detai;
            } else {
                hint.style.display = 'none';
                input.value = '';
            }
        }
    </script>

@endsection
