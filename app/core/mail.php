<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Creates and returns a pre-configured PHPMailer instance.
 * Uses Gmail SMTP with App Password authentication.
 */
function getMailer(): PHPMailer
{
    $mail = new PHPMailer(true); // true = throw exceptions on error

    // ── SMTP Settings ───────────────────────────────────────
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fernanjoshcarl7@gmail.com';
    $mail->Password   = 'tqcgtfeumtounzza'; // Google App Password — NO spaces
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ── Sender Identity ─────────────────────────────────────
    $mail->setFrom('fernanjoshcarl7@gmail.com', 'Library System');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    return $mail;
}

/**
 * Send an OTP email to a user.
 *
 * @param  string $email  Recipient email address
 * @param  string $otp    The OTP code to send
 * @param  string $refNo  Reference number for the transaction
 * @return bool           True on success, false on failure
 */
function sendOtpMail(string $email, string $otp, string $refNo): bool
{
    try {
        $mail = getMailer();

        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code – Library System';

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; line-height: 1.6; color: #333;'>
            <div style='background: #1a1a2e; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #fff; margin: 0;'>📚 Library System</h2>
            </div>
            <div style='background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #ddd;'>
                <p>Hello,</p>
                <p>Reference Number: <b>{$refNo}</b></p>
                <p>Your One-Time Password (OTP) is:</p>
                <p style='font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #1a1a2e; text-align: center;'>
                    {$otp}
                </p>
                <p style='color: #888; font-size: 13px;'>⏳ This OTP will expire in <b>10 minutes</b>. Do not share it with anyone.</p>
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 12px; color: #aaa;'>If you did not request this, please ignore this email.</p>
            </div>
        </div>
        ";

        $mail->AltBody = "Reference: {$refNo} | Your OTP is: {$otp} (expires in 10 minutes)";

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Log the real error privately; do NOT expose it to users
        error_log('sendOtpMail failed [' . $email . ']: ' . $e->getMessage());
        return false;
    }
}
