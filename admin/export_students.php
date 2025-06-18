<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require_once '../includes/Database.php';
require_once '../models/Student.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    // Lấy dữ liệu học sinh
    $student = new Student();
    $result = $student->getAll();
    $students = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

    // Tạo một Spreadsheet mới
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Đặt tiêu đề cho các cột
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Họ và tên');
    $sheet->setCellValue('C1', 'Biệt danh');
    $sheet->setCellValue('D1', 'Tuổi');
    $sheet->setCellValue('E1', 'Tên phụ huynh');
    $sheet->setCellValue('F1', 'Số điện thoại');
    $sheet->setCellValue('G1', 'Địa chỉ');
    $sheet->setCellValue('H1', 'Ghi chú');
    $sheet->setCellValue('I1', 'Ngày tạo');

    // Định dạng tiêu đề
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => '000000'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'CCCCCC'],
        ],
    ];
    $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

    // Thêm dữ liệu
    $row = 2;
    foreach ($students as $student) {
        $sheet->setCellValue('A' . $row, $student['id']);
        $sheet->setCellValue('B' . $row, $student['full_name']);
        $sheet->setCellValue('C' . $row, $student['nick_name']);
        $sheet->setCellValue('D' . $row, $student['age']);
        $sheet->setCellValue('E' . $row, $student['parent_name']);
        $sheet->setCellValue('F' . $row, $student['phone']);
        $sheet->setCellValue('G' . $row, $student['address']);
        $sheet->setCellValue('H' . $row, $student['notes']);
        $sheet->setCellValue('I' . $row, $student['created_at']);
        $row++;
    }

    // Tự động điều chỉnh độ rộng cột
    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Tạo file Excel
    $writer = new Xlsx($spreadsheet);
    
    // Đặt header để download file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="danh_sach_hoc_sinh.xlsx"');
    header('Cache-Control: max-age=0');

    // Xuất file
    $writer->save('php://output');
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Lỗi khi xuất file Excel: " . $e->getMessage();
    header('Location: index.php?page=students');
    exit();
} 