<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'email_config.php';

/**
 * Kirim email aktivasi akun
 */
function sendActivationEmail($to_email, $to_name, $activation_token) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port       = SMTP_PORT;
        
        // Set charset
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        
        // Content
        $activation_link = BASE_URL . '/activate.php?token=' . $activation_token;
        
        $mail->isHTML(true);
        $mail->Subject = 'Aktivasi Akun - Sistem Manajemen User';
        $mail->Body    = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .button { 
                        display: inline-block; 
                        padding: 10px 20px; 
                        background-color: #4CAF50; 
                        color: white; 
                        text-decoration: none; 
                        border-radius: 5px; 
                    }
                    .footer { margin-top: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Aktivasi Akun Admin Gudang</h2>
                    </div>
                    <div class='content'>
                        <p>Halo <strong>$to_name</strong>,</p>
                        <p>Terima kasih telah mendaftar sebagai <strong>Admin Gudang</strong> di Sistem Manajemen User.</p>
                        <p>Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
                        <p style='text-align: center;'>
                            <a href='$activation_link' class='button'>Aktivasi Akun Saya</a>
                        </p>
                        <p>Atau copy dan paste link berikut ke browser Anda:</p>
                        <p><a href='$activation_link'>$activation_link</a></p>
                        <p>Link aktivasi ini berlaku selama 24 jam.</p>
                        <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
                    </div>
                    <div class='footer'>
                        <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
                        <p>&copy; 2025 Sistem Manajemen User. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mail->AltBody = "Halo $to_name,\n\nUntuk mengaktifkan akun Anda, klik link berikut:\n$activation_link\n\nLink berlaku 24 jam.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email activation gagal: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Kirim email reset password
 */
function sendPasswordResetEmail($to_email, $to_name, $reset_token) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port       = SMTP_PORT;
        
        // Set charset
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        
        // Content
        $reset_link = BASE_URL . '/reset_password.php?token=' . $reset_token;
        
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password - Sistem Manajemen User';
        $mail->Body    = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #2196F3; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .button { 
                        display: inline-block; 
                        padding: 10px 20px; 
                        background-color: #2196F3; 
                        color: white; 
                        text-decoration: none; 
                        border-radius: 5px; 
                    }
                    .footer { margin-top: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Reset Password</h2>
                    </div>
                    <div class='content'>
                        <p>Halo <strong>$to_name</strong>,</p>
                        <p>Kami menerima permintaan untuk reset password akun Anda.</p>
                        <p>Untuk membuat password baru, silakan klik tombol di bawah ini:</p>
                        <p style='text-align: center;'>
                            <a href='$reset_link' class='button'>Reset Password</a>
                        </p>
                        <p>Atau copy dan paste link berikut ke browser Anda:</p>
                        <p><a href='$reset_link'>$reset_link</a></p>
                        <p>Link reset password ini berlaku selama 1 jam.</p>
                        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
                    </div>
                    <div class='footer'>
                        <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
                        <p>&copy; 2025 Sistem Manajemen User. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mail->AltBody = "Halo $to_name,\n\nUntuk reset password Anda, klik link berikut:\n$reset_link\n\nLink berlaku 1 jam.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email reset password gagal: {$mail->ErrorInfo}");
        return false;
    }
}
?>