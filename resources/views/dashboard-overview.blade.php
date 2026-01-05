@extends('layouts.app')

@section('title', 'Tổng quan')

@section('content')
    @php
        $isGiangVien = ($role ?? auth()->user()->vaitro) === 'giangvien';

        // Hiển thị số theo role
        $svCount = $isGiangVien ? $mySinhVienCount ?? 0 : $totalSinhVien ?? 0;
        $gvCount = $totalGiangVien ?? 0;
        $dtCount = $isGiangVien ? $myDeTaiCount ?? 0 : $totalDeTai ?? 0;
    @endphp

    <div class="dash">
        <div class="dash__header">
            <div>
                <h1 class="dash__title">Tổng quan</h1>
                <p class="dash__subtitle">
                    {{ $isGiangVien ? 'Thống kê theo phạm vi bạn được phân công.' : 'Thống kê tổng quan hệ thống.' }}
                </p>
            </div>
        </div>

        {{-- Cards --}}
        <div class="dash__cards {{ $isGiangVien ? 'dash__cards--center' : '' }}">
            {{-- Card Sinh viên --}}
            <div class="stat-card stat-card--blue">
                <div class="stat-card__left">
                    <div class="stat-card__label">{{ $isGiangVien ? 'Sinh viên  hướng dẫn' : 'Tổng sinh viên' }}</div>
                    <div class="stat-card__value">{{ $svCount }}</div>
                </div>
                <div class="stat-card__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
            </div>

            {{-- Card Giảng viên (chỉ Admin) --}}
            @if (!$isGiangVien)
                <div class="stat-card stat-card--green">
                    <div class="stat-card__left">
                        <div class="stat-card__label">Tổng giảng viên</div>
                        <div class="stat-card__value">{{ $gvCount }}</div>
                    </div>
                    <div class="stat-card__icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Card Đề tài --}}
            <div class="stat-card stat-card--orange">
                <div class="stat-card__left">
                    <div class="stat-card__label">{{ $isGiangVien ? 'Tổng đề tài' : 'Tổng đề tài' }}</div>
                    <div class="stat-card__value">{{ $dtCount }}</div>
                </div>
                <div class="stat-card__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Lower section: giúp đỡ + tóm tắt (đỡ trống) --}}
        <div class="dash__grid">
            <div class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Gợi ý thao tác</h2>
                    <p class="panel__desc">
                        {{ $isGiangVien ? 'Một vài tác vụ hay dùng cho giảng viên.' : 'Một vài tác vụ hay dùng cho quản trị.' }}
                    </p>
                </div>

                <div class="panel__actions">
                    @if ($isGiangVien)
                        <a class="btn-soft" href="{{ route('theo-doi-tien-do.index') }}">✏️ Đánh giá giữa kỳ</a>
                        <a class="btn-soft" href="{{ route('cham-diem-hd.index') }}">📝 Chấm điểm hướng dẫn</a>
                        <a class="btn-soft" href="{{ route('phancong-detai.index') }}">📌 Xem phân công đề tài</a>
                    @else
                        <a class="btn-soft" href="{{ route('sinhvien.index') }}">👥 Quản lý sinh viên</a>
                        <a class="btn-soft" href="{{ route('giangvien.index') }}">👨‍🏫 Quản lý giảng viên</a>
                        <a class="btn-soft" href="{{ route('hoi-dong.index') }}">🏛️ Hội đồng LVTN</a>
                    @endif
                </div>
            </div>

            <div class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Tóm tắt nhanh</h2>
                    <p class="panel__desc">Bạn có thể bổ sung thêm số liệu ở đây sau.</p>
                </div>

                <div class="summary">
                    <div class="summary__item">
                        <div class="summary__label">Hôm nay</div>
                        <div class="summary__value">{{ now()->format('d/m/Y') }}</div>
                    </div>
                    <div class="summary__item">
                        <div class="summary__label">Vai trò</div>
                        <div class="summary__value">{{ $isGiangVien ? 'Giảng viên' : 'Quản trị' }}</div>
                    </div>
                    <div class="summary__item">
                        <div class="summary__label">Ghi chú</div>
                        <div class="summary__value summary__muted">—</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
