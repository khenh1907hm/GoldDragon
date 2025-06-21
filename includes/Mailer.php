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
            $this->mailer->Subject = '[Rong Vang School] New Enrollment Registration';
            
            // Create email body
            $body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
                    .wrapper { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
                    .header { background-color: #fca311; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .header h2 { margin: 0; font-size: 24px; }
                    .content { padding: 20px; }
                    .content h3 { color: #14213d; border-bottom: 2px solid #fca311; padding-bottom: 5px; }
                    .info-block { margin-bottom: 15px; }
                    .info-block p { background-color: #fff; padding: 10px; border-radius: 5px; border-left: 4px solid #e5e5e5; margin: 5px 0; }
                    .info-block .label { font-weight: bold; color: #14213d; display: inline-block; min-width: 140px; }
                    .footer { text-align: center; margin-top: 20px; font-size: 0.9em; color: #666; }
                </style>
            </head>
            <body>
                <div class='wrapper'>
                    <div class='header'>
                        <h2>New Enrollment Registration</h2>
                    </div>
                    <div class='content'>
                        <p>Hello Admin,</p>
                        <p>A new registration has been submitted through the website. Here are the details:</p>
                        
                        <h3>Student Information</h3>
                        <div class='info-block'>
                            <p><span class='label'>Student's Name:</span> " . htmlspecialchars($registration['student_name']) . "</p>
                            <p><span class='label'>Nickname:</span> " . htmlspecialchars($registration['nick_name']) . "</p>
                            <p><span class='label'>Age:</span> " . htmlspecialchars($registration['age']) . "</p>
                        </div>

                        <h3>Parent/Guardian Information</h3>
                        <div class='info-block'>
                            <p><span class='label'>Parent's Name:</span> " . htmlspecialchars($registration['parent_name']) . "</p>
                            <p><span class='label'>Phone Number:</span> " . htmlspecialchars($registration['phone']) . "</p>
                            <p><span class='label'>Address:</span> " . htmlspecialchars($registration['address']) . "</p>
                        </div>

                        <h3>Additional Message</h3>
                        <div class='info-block'>
                            <p>" . nl2br(htmlspecialchars($registration['content'])) . "</p>
                        </div>

                        <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>

                        <p><strong>Registration Time:</strong> " . htmlspecialchars($registration['created_at']) . "</p>
                        <p>Please follow up with the parent as soon as possible.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Rong Vang Kindergarten | This is an automated notification.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "New Registration:\n" .
                                     "Student Name: " . $registration['student_name'] . "\n" .
                                     "Parent Name: " . $registration['parent_name'] . "\n" .
                                     "Phone: " . $registration['phone'] . "\n" .
                                     "Message: " . $registration['content'];
            
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