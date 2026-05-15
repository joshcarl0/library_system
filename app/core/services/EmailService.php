<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

class EmailService {
    // IMPORTANT: USER needs to fill these or we use a config file
    private $host = 'smtp.gmail.com';
    private $port = 587;

    private $username = 'fernanjoshcarl7@gmail.com'; 
    private $password = 'tqcgtfeumtounzza';   
    private $fromName = 'Olivarez College Library';

    public function sendDueReminder($toEmail, $toName, $bookTitle, $dueDate) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->port;

            // Recipients
            $mail->setFrom($this->username, $this->fromName);
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Library Resource Due Date Reminder';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                    <h2 style='color: #0d3612;'>Hello, $toName!</h2>
                    <p>This is a friendly reminder from <strong>Olivarez College Library</strong>.</p>
                    <p>The following resource is due for return today:</p>
                    <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #d4af37; margin: 20px 0;'>
                        <strong>Resource:</strong> $bookTitle <br>
                        <strong>Due Date:</strong> " . date('F j, Y', strtotime($dueDate)) . "
                    </div>
                    <p>Please return the item to the library to avoid any penalties.</p>
                    <p style='color: #666; font-size: 0.9rem;'>Thank you for your cooperation!</p>
                    <hr style='border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 0.8rem; color: #999;'>This is an automated message, please do not reply.</p>
                </div>
            ";


            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendOtp($toEmail, $toName, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->port;

            $mail->setFrom($this->username, $this->fromName);
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Library Account';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 25px; border: 1px solid #e2e8f0; border-radius: 15px; max-width: 500px; margin: auto;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #0d3612; margin: 0;'>Olivarez College</h2>
                        <p style='color: #d4af37; font-weight: bold; margin: 5px 0;'>Library Resource Information System</p>
                    </div>
                    <p>Hi <strong>$toName</strong>,</p>
                    <p>Thank you for registering. To complete your account setup, please enter the verification code below:</p>
                    <div style='background: #f8fafc; padding: 20px; text-align: center; border-radius: 12px; margin: 25px 0; border: 1px dashed #cbd5e1;'>
                        <span style='font-size: 2.5rem; font-weight: 800; letter-spacing: 5px; color: #0d3612;'>$otp</span>
                    </div>
                    <p style='font-size: 0.9rem; color: #64748b;'>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
                    <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                    <p style='font-size: 0.8rem; color: #94a3b8; text-align: center;'>&copy; " . date('Y') . " Olivarez College Library. All rights reserved.</p>
                </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("OTP Email failed: {$mail->ErrorInfo}");
            return false;
        }
    }
}
