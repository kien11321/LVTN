<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuByRole($role)
    {
        $menus = [];

        // Menu cho Admin
        if ($role === 'admin') {
            $menus = [
                [
                    'title' => 'Quản lý Sinh viên',
                    'icon' => '👥',
                    'children' => [
                        ['title' => 'Danh sách sinh viên', 'route' => 'sinhvien.index'],
                        ['title' => 'Phân công sinh viên', 'route' => 'phancong.index'],
                        ['title' => 'Sinh viên chưa có đề tài', 'route' => 'sinhvien.nhom-chua-de-tai'],
                    ],
                ],
                [
                    'title' => 'Quản lý Giảng viên',
                    'icon' => '👨‍🏫',
                    'children' => [
                        ['title' => 'Danh sách Giảng viên', 'route' => 'giangvien.index'],
                        ['title' => 'Phân công Phản biện', 'route' => 'phan-bien.index'],
                    ],
                ],
                [
                    'title' => 'Hội đồng LVTN',
                    'icon' => '🏛️',
                    'route' => 'hoi-dong.index',
                ],
                [
                    'title' => 'Nhập Liệu',
                    'icon' => '📝',
                    'children' => [
                        ['title' => 'Tạo Phiếu Nhiệm vụ cho đề tài', 'route' => 'tao-phieu-giao-detai.index'],
                        ['title' => 'GVHD Chấm Điểm', 'route' => 'cham-diem-hd.index'],
                        ['title' => 'GVPB Chấm Điểm', 'route' => 'cham-diem-pb.index'],
                        ['title' => 'Nhập điểm Hội đồng', 'route' => 'nhap-diem-hoi-dong.index'],
                    ],
                ],
                [
                    'title' => 'Phân công đề tài',
                    'icon' => '📋',
                    'route' => 'phancong-detai.index',
                ],
                [
                    'title' => 'Theo dõi tiến độ',
                    'icon' => '📊',
                    'route' => 'theo-doi-tien-do.index',
                ],
            ];
        }

        // Menu cho Giảng viên hướng dẫn (GVHD)
        if ($role === 'gvhd' || $role === 'giangvien' || $role === 'gvpb') {
            $menus = [
                // [
                //     'title' => 'Quản lý Sinh viên',
                //     'icon' => '👥',
                //     'children' => [
                       

                //     ],
                // ],
                 ['title' => 'Danh sách sinh viên', 'route' => 'sinhvien.index'],
               

                [
                    'title' => 'Nhập Liệu',
                    'icon' => '📝',
                    'children' => [
                        ['title' => 'Chấm điểm hướng dẫn', 'route' => 'cham-diem-hd.index'],
                        ['title' => 'Chấm điểm phản biện', 'route' => 'cham-diem-pb.index'],
                        ['title' => 'Nhập điểm Hội đồng', 'route' => 'nhap-diem-hoi-dong.index'],
                    ],
                ],
                [
                    'title' => 'Phân công đề tài',
                    'icon' => '📋',
                    'route' => 'phancong-detai.index',
                ],
                [
                    'title' => 'Theo dõi tiến độ',
                    'icon' => '📊',
                    'route' => 'theo-doi-tien-do.index',
                ],
            ];
        }

        // Menu mặc định (nếu không match role nào)
        if (empty($menus)) {
            $menus = [
                [
                    'title' => 'Tổng quan',
                    'icon' => '📊',
                    'route' => 'dashboard',
                ],
            ];
        }

        return $menus;
    }

    /**
     * Kiểm tra menu item có active không
     */
    public static function isActive($route, $params = [])
    {
        if (!request()->routeIs($route)) {
            return false;
        }

        // Nếu có params, phải match tất cả
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                // Nếu param không tồn tại trong request, không active
                if (!request()->has($key)) {
                    return false;
                }
                // Nếu param tồn tại nhưng giá trị khác, không active
                if (request()->get($key) != $value) {
                    return false;
                }
            }
            // Tất cả params đều match
            return true;
        }

        // Nếu không có params, chỉ active khi route match
        // (Không cần kiểm tra params vì không có params nào được định nghĩa)
        return true;
    }

    /**
     * Kiểm tra menu có children active không
     */
    public static function hasActiveChild($children, $activeMainMenuRoute = null)
    {
        if (empty($children)) {
            return false;
        }

        foreach ($children as $child) {
            if (isset($child['route'])) {
                // Nếu có menu chính cùng route đang active, không tính submenu này
                if ($activeMainMenuRoute && $activeMainMenuRoute === $child['route']) {
                    continue;
                }
                if (self::isActive($child['route'], $child['params'] ?? [])) {
                    return true;
                }
            }
        }

        return false;
    }
}
