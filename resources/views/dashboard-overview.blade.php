@extends('layouts.app')

@section('title', 'Tổng quan')

@section('content')
    <h1 class="page-title">Tổng quan</h1>

    <!-- Dashboard Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Card Sinh viên -->
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 8px;">Tổng sinh viên</div>
                    <div style="font-size: 32px; font-weight: 600; color: #333;">
                        {{ DB::table('sinhvien')->count() }}
                    </div>
                </div>
                <div style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#2196f3" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Giảng viên -->
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 8px;">Tổng giảng viên</div>
                    <div style="font-size: 32px; font-weight: 600; color: #333;">
                        {{ DB::table('giangvien')->count() }}
                    </div>
                </div>
                <div style="width: 60px; height: 60px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#4caf50" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Đề tài -->
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 8px;">Tổng đề tài</div>
                    <div style="font-size: 32px; font-weight: 600; color: #333;">
                        {{ DB::table('detai')->count() }}
                    </div>
                </div>
                <div style="width: 60px; height: 60px; background: #fff3e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#ff9800" viewBox="0 0 24 24">
                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Phân công -->
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 8px;">Đã phân công</div>
                    <div style="font-size: 32px; font-weight: 600; color: #333;">
                        {{ DB::table('phancong')->count() }}
                    </div>
                </div>
                <div style="width: 60px; height: 60px; background: #fce4ec; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#e91e63" viewBox="0 0 24 24">
                        <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #333;">Truy cập nhanh</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="{{ route('sinhvien.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 15px; background: #f5f5f5; border-radius: 6px; text-decoration: none; color: #333; transition: background 0.3s;" onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                <svg width="24" height="24" fill="#2196f3" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
                <span style="font-weight: 500;">Quản lý sinh viên</span>
            </a>
            <a href="{{ route('giangvien.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 15px; background: #f5f5f5; border-radius: 6px; text-decoration: none; color: #333; transition: background 0.3s;" onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                <svg width="24" height="24" fill="#4caf50" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span style="font-weight: 500;">Quản lý giảng viên</span>
            </a>
            <a href="{{ route('phancong.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 15px; background: #f5f5f5; border-radius: 6px; text-decoration: none; color: #333; transition: background 0.3s;" onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                <svg width="24" height="24" fill="#e91e63" viewBox="0 0 24 24">
                    <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                </svg>
                <span style="font-weight: 500;">Phân công hướng dẫn</span>
            </a>
        </div>
    </div>
@endsection













