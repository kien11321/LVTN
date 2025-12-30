<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\NhapDiemHoiDongController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\PhanCongController;
use App\Http\Controllers\TheoDoiTienDoController;
use App\Http\Controllers\PhanCongDeTaiController;
use App\Http\Controllers\PhanBienController;
use App\Http\Controllers\ChamDiemHuongDanController;
use App\Http\Controllers\ChamDiemPhanBienController;
use App\Http\Controllers\TaoPhieuGiaoDeTaiController;
use App\Http\Controllers\HoiDongController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Routes đăng nhập (không cần đăng ký)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route tổng quan (dashboard)
Route::get('/dashboard', function () {
    return view('dashboard-overview');
})->name('dashboard')->middleware('auth');

// Routes quản lý sinh viên (CRUD)
Route::middleware(['auth'])->group(function () {
    Route::get('/sinhvien', [SinhVienController::class, 'index'])->name('sinhvien.index');
    Route::get('/sinhvien/create', [SinhVienController::class, 'create'])->name('sinhvien.create');
    Route::post('/sinhvien', [SinhVienController::class, 'store'])->name('sinhvien.store');
    Route::get('/sinhvien/{id}/edit', [SinhVienController::class, 'edit'])->name('sinhvien.edit');
    Route::put('/sinhvien/{id}', [SinhVienController::class, 'update'])->name('sinhvien.update');
    Route::delete('/sinhvien/{id}', [SinhVienController::class, 'destroy'])->name('sinhvien.destroy');
    Route::post('/sinhvien/update-nhom', [SinhVienController::class, 'updateNhom'])->name('sinhvien.update-nhom');
    Route::post('/sinhvien/import', [SinhVienController::class, 'import'])->name('sinhvien.import.post');
    Route::get('/nhom-chua-co-de-tai', [SinhVienController::class, 'nhomChuaCoDeTai'])->name('sinhvien.nhom-chua-de-tai');
});

// Routes quản lý giảng viên
Route::resource('giangvien', GiangVienController::class)->middleware('auth');

// Routes phân công hướng dẫn
Route::middleware(['auth'])->group(function () {
    Route::get('/phancong', [PhanCongController::class, 'index'])->name('phancong.index');
    Route::post('/phancong/update', [PhanCongController::class, 'update'])->name('phancong.update');
    Route::post('/phancong/update-nhom', [PhanCongController::class, 'updateNhom'])->name('phancong.update-nhom');
});

// Routes theo dõi tiến độ
Route::middleware(['auth', 'role:admin,gvhd,giangvien,gvpb'])->group(function () {
    Route::get('/theo-doi-tien-do', [TheoDoiTienDoController::class, 'index'])->name('theo-doi-tien-do.index');
    Route::get('/theo-doi-tien-do/{nhomId}/edit', [TheoDoiTienDoController::class, 'edit'])->name('theo-doi-tien-do.edit');
    Route::put('/theo-doi-tien-do/{nhomId}', [TheoDoiTienDoController::class, 'update'])->name('theo-doi-tien-do.update');
    Route::get('/theo-doi-tien-do/export', [TheoDoiTienDoController::class, 'exportCsv'])->name('theo-doi-tien-do.export');
    Route::get('/theo-doi-tien-do/export-excel', [TheoDoiTienDoController::class, 'exportExcel'])->name('theo-doi-tien-do.export-excel');

    // Phân công Giảng viên Phản biện
    Route::get('/phan-bien', [PhanBienController::class, 'index'])->name('phan-bien.index');
    Route::post('/phan-bien', [PhanBienController::class, 'update'])->name('phan-bien.update');
    Route::get('/phan-bien/export', [PhanBienController::class, 'exportCsv'])->name('phan-bien.export');

    // Chấm điểm GV hướng dẫn
    Route::get('/cham-diem-hd', [ChamDiemHuongDanController::class, 'index'])->name('cham-diem-hd.index');
    Route::post('/cham-diem-hd', [ChamDiemHuongDanController::class, 'store'])->name('cham-diem-hd.store');
    Route::get('/cham-diem-hd/{nhomId}/export-word', [ChamDiemHuongDanController::class, 'exportWord'])->name('cham-diem-hd.export-word');
    Route::get('/cham-diem-hd/export-excel', [ChamDiemHuongDanController::class, 'exportExcel'])->name('cham-diem-hd.export-excel');

    // Chấm điểm GV phản biện
    Route::get('/cham-diem-pb', [ChamDiemPhanBienController::class, 'index'])->name('cham-diem-pb.index');
    Route::post('/cham-diem-pb', [ChamDiemPhanBienController::class, 'store'])->name('cham-diem-pb.store');
    Route::get('/cham-diem-pb/{nhomId}/export-word', [ChamDiemPhanBienController::class, 'exportWord'])->name('cham-diem-pb.export-word');

    // Tạo phiếu giao đề tài
    Route::get('/tao-phieu-giao-detai', [TaoPhieuGiaoDeTaiController::class, 'index'])->name('tao-phieu-giao-detai.index');
    Route::post('/tao-phieu-giao-detai/export-word', [TaoPhieuGiaoDeTaiController::class, 'exportWord'])->name('tao-phieu-giao-detai.export-word');

    // Hội đồng LVTN
    Route::get('/hoi-dong', [HoiDongController::class, 'index'])->name('hoi-dong.index');
    Route::post('/hoi-dong', [HoiDongController::class, 'store'])->name('hoi-dong.store');
    Route::put('/hoi-dong/{id}', [HoiDongController::class, 'update'])->name('hoi-dong.update');
    Route::delete('/hoi-dong/{id}', [HoiDongController::class, 'destroy'])->name('hoi-dong.destroy');
    Route::post('/hoi-dong/assign-detai', [HoiDongController::class, 'assignDetai'])->name('hoi-dong.assign-detai');
    Route::post('/hoi-dong/unassign-detai', [HoiDongController::class, 'unassignDetai'])->name('hoi-dong.unassign-detai');
    Route::get('/hoi-dong/export', [HoiDongController::class, 'exportExcel'])->name('hoi-dong.export');

    // Nhập điểm hội đồng
    Route::get('/nhap-diem-hoi-dong', [NhapDiemHoiDongController::class, 'index'])->name('nhap-diem-hoi-dong.index');
    Route::post('/nhap-diem-hoi-dong', [NhapDiemHoiDongController::class, 'store'])->name('nhap-diem-hoi-dong.store');
    Route::get('/nhap-diem-hoi-dong/export-excel', [NhapDiemHoiDongController::class, 'exportExcel'])->name('nhap-diem-hoi-dong.export-excel');
});

// Routes phân công đề tài cho nhóm (Quản lí & Giảng viên)
Route::middleware(['auth', 'role:admin,gvhd,giangvien,gvpb'])->group(function () {
    Route::get('/phancong-detai', [PhanCongDeTaiController::class, 'index'])->name('phancong-detai.index');
    Route::post('/phancong-detai/store-simple', [PhanCongDeTaiController::class, 'storeSimple'])->name('phancong-detai.store-simple');

    Route::get('/phancong-detai/create/{nhomId}', [PhanCongDeTaiController::class, 'create'])->name('phancong-detai.create');
    Route::post('/phancong-detai/store', [PhanCongDeTaiController::class, 'store'])->name('phancong-detai.store');
    Route::delete('/phancong-detai/{nhomId}', [PhanCongDeTaiController::class, 'destroy'])->name('phancong-detai.destroy');

    Route::get('/api/phancong-detai/search', [PhanCongDeTaiController::class, 'searchDeTai'])->name('phancong-detai.search');

    Route::get('/phancong-detai/danh-sach', [PhanCongDeTaiController::class, 'danhSachDeTai'])->name('phancong-detai.danh-sach');
    Route::get('/phancong-detai/tao-detai', [PhanCongDeTaiController::class, 'createDeTai'])->name('phancong-detai.create-detai');
    Route::post('/phancong-detai/luu-detai', [PhanCongDeTaiController::class, 'storeDeTai'])->name('phancong-detai.store-detai');
});
