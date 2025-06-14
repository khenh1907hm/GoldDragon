<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private $mailer;
    private $adminEmail = 'hminh19072003@gmail.com';

    public function __construct() {
        try {
            $this->mailer = new PHPMailer(true);
            
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.gmail.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'hminh19072003@gmail.com';
            $this->mailer->Password = 'bjwy adcp wcgj vgvr'; // TODO: Add your Gmail App Password here. Get it from Google Account > Security > 2-Step Verification > App passwords
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = 587;
            $this->mailer->CharSet = 'UTF-8';

            // Default sender
            $this->mailer->setFrom('hminh19072003@gmail.com', 'Rong Vang Kindergarten');
            
            error_log("Mailer initialized successfully");
        } catch (Exception $e) {
            error_log("Mailer Initialization Error: " . $e->getMessage());
            throw new Exception("Failed to initialize mailer");
        }
    }

    public function sendNewRegistrationNotification($registration) {
        try {
            error_log("Preparing to send registration notification email");
            
            // Reset all recipients
            $this->mailer->clearAddresses();
            
            // Add recipient
            $this->mailer->addAddress($this->adminEmail);
            
            // Set email content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'New Registration Notification';
            
            // Create email body
            $body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                    }
                    .header {
                        background-color: #4CAF50;
                        color: white;
                        padding: 20px;
                        text-align: center;
                        border-radius: 5px 5px 0 0;
                    }
                    .content {
                        background-color: #f9f9f9;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 0 0 5px 5px;
                    }
                    .info-block {
                        background-color: white;
                        padding: 15px;
                        margin: 10px 0;
                        border-radius: 5px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .label {
                        font-weight: bold;
                        color: #4CAF50;
                        display: inline-block;
                        width: 150px;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 20px;
                        padding-top: 20px;
                        border-top: 1px solid #ddd;
                        color: #666;
                        font-size: 0.9em;
                    }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h2>Thông Báo Đăng Ký Mới</h2>
                    <p>Trường Mầm Non Rồng Vàng</p>
                </div>
                <div class='content'>
                    <p>Kính gửi Ban Giám Hiệu,</p>
                    <p>Chúng tôi vừa nhận được một đăng ký mới từ phụ huynh. Dưới đây là thông tin chi tiết:</p>
                    
                    <div class='info-block'>
                        <p><span class='label'>Họ tên bé:</span> {$registration['student_name']}</p>
                        <p><span class='label'>Tên thường gọi:</span> {$registration['nick_name']}</p>
                        <p><span class='label'>Tuổi:</span> {$registration['age']}</p>
                    </div>

                    <div class='info-block'>
                        <p><span class='label'>Họ tên phụ huynh:</span> {$registration['parent_name']}</p>
                        <p><span class='label'>Số điện thoại:</span> {$registration['phone']}</p>
                        <p><span class='label'>Địa chỉ:</span> {$registration['address']}</p>
                    </div>

                    <div class='info-block'>
                        <p><span class='label'>Nội dung:</span></p>
                        <p>{$registration['content']}</p>
                    </div>

                    <div class='info-block'>
                        <p><span class='label'>Thời gian đăng ký:</span> {$registration['created_at']}</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>Đây là email tự động từ hệ thống đăng ký của Trường Mầm Non Rồng Vàng</p>
                <h2>New Registration Received</h2>
                <p><strong>Student Name:</strong> {$registration['student_name']}</p>
                <p><strong>Nick Name:</strong> {$registration['nick_name']}</p>
                <p><strong>Age:</strong> {$registration['age']}</p>
                <p><strong>Parent Name:</strong> {$registration['parent_name']}</p>
                <p><strong>Phone:</strong> {$registration['phone']}</p>
                <p><strong>Address:</strong> {$registration['address']}</p>
                <p><strong>Content:</strong> {$registration['content']}</p>
                <p><strong>Registration Time:</strong> {$registration['created_at']}</p>
            ";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($body);
            
            // Send email
            $this->mailer->send();
            error_log("Registration notification email sent successfully");
            
            return true;
        } catch (Exception $e) {
            error_log("Send Email Error: " . $e->getMessage());
            error_log("SMTP Debug Info: " . $this->mailer->ErrorInfo);
            throw new Exception("Failed to send email notification: " . $e->getMessage());
        }
    }
} 