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
            $this->mailer->Password = 'your_app_password'; // Use App Password from Google Account
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
            throw new Exception("Failed to send email notification");
        }
    }
} 