<?php
/**
 * Production Email Configuration for DreamLock
 * Replace the mail() function in forgot_password.php with this setup
 */

// Option 1: Using PHPMailer (Recommended)
// First install: composer require phpmailer/phpmailer

/*
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendPasswordResetEmail($to, $username, $resetLink) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // or your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';
        $mail->Password   = 'your-app-password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('noreply@dreamlock.com', 'DreamLock');
        $mail->addAddress($to, $username);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'DreamLock - Şifre Sıfırlama';
        $mail->Body = generateEmailTemplate($username, $resetLink);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}

function generateEmailTemplate($username, $resetLink) {
    return "
    <html>
    <head>
        <title>Şifre Sıfırlama</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #39FF14; margin: 0;'>DREAMLOCK</h1>
                <p style='color: #666; margin: 10px 0;'>Şifre Sıfırlama İsteği</p>
            </div>
            
            <div style='background: #f9f9f9; padding: 30px; border-radius: 10px; border-left: 4px solid #39FF14;'>
                <h2 style='color: #333; margin-top: 0;'>Merhaba {$username},</h2>
                
                <p>DreamLock hesabınız için şifre sıfırlama talebinde bulundunuz.</p>
                
                <p>Şifrenizi sıfırlamak için aşağıdaki butona tıklayın:</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetLink}' style='background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%); color: #000; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>Şifremi Sıfırla</a>
                </div>
                
                <p style='font-size: 14px; color: #666;'>Bu link 24 saat geçerlidir.</p>
                
                <p style='font-size: 14px; color: #666;'>Eğer bu talebi siz yapmadıysanız, bu emaili görmezden gelebilirsiniz.</p>
            </div>
            
            <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                <p style='color: #666; font-size: 12px;'>
                    Bu email DreamLock sisteminden otomatik olarak gönderilmiştir.<br>
                    Lütfen bu emaili yanıtlamayın.
                </p>
            </div>
        </div>
    </body>
    </html>";
}
*/

// Option 2: Using SendGrid (Alternative)
/*
function sendPasswordResetEmail($to, $username, $resetLink) {
    $url = 'https://api.sendgrid.com/';
    $user = 'your-sendgrid-username';
    $pass = 'your-sendgrid-password';
    
    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'to'        => $to,
        'subject'   => 'DreamLock - Şifre Sıfırlama',
        'html'      => generateEmailTemplate($username, $resetLink),
        'from'      => 'noreply@dreamlock.com',
        'fromname'  => 'DreamLock'
    );
    
    $request = $url.'api/mail.send.json';
    
    $session = curl_init($request);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($session);
    curl_close($session);
    
    return $response !== false;
}
*/

// Option 3: Using Mailgun (Another Alternative)
/*
function sendPasswordResetEmail($to, $username, $resetLink) {
    $api_key = 'your-mailgun-api-key';
    $domain = 'your-domain.com';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "api:{$api_key}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/{$domain}/messages");
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'from'    => 'DreamLock <noreply@dreamlock.com>',
        'to'      => $to,
        'subject' => 'DreamLock - Şifre Sıfırlama',
        'html'    => generateEmailTemplate($username, $resetLink)
    ));
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result !== false;
}
*/

// Option 4: Simple SMTP with fsockopen (No external libraries)
function sendPasswordResetEmail($to, $username, $resetLink) {
    $smtp_server = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_username = 'your-email@gmail.com';
    $smtp_password = 'your-app-password';
    
    $email_content = generateEmailTemplate($username, $resetLink);
    
    // Create email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: DreamLock <noreply@dreamlock.com>',
        'Reply-To: noreply@dreamlock.com',
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Use WordPress wp_mail if available
    if (function_exists('wp_mail')) {
        return wp_mail($to, 'DreamLock - Şifre Sıfırlama', $email_content, implode("\r\n", $headers));
    }
    
    // Fallback to mail() function (not recommended for production)
    return mail($to, 'DreamLock - Şifre Sıfırlama', $email_content, implode("\r\n", $headers));
}

function generateEmailTemplate($username, $resetLink) {
    return "
    <html>
    <head>
        <title>Şifre Sıfırlama</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #39FF14; margin: 0;'>DREAMLOCK</h1>
                <p style='color: #666; margin: 10px 0;'>Şifre Sıfırlama İsteği</p>
            </div>
            
            <div style='background: #f9f9f9; padding: 30px; border-radius: 10px; border-left: 4px solid #39FF14;'>
                <h2 style='color: #333; margin-top: 0;'>Merhaba {$username},</h2>
                
                <p>DreamLock hesabınız için şifre sıfırlama talebinde bulundunuz.</p>
                
                <p>Şifrenizi sıfırlamak için aşağıdaki butona tıklayın:</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetLink}' style='background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%); color: #000; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>Şifremi Sıfırla</a>
                </div>
                
                <p style='font-size: 14px; color: #666;'>Bu link 24 saat geçerlidir.</p>
                
                <p style='font-size: 14px; color: #666;'>Eğer bu talebi siz yapmadıysanız, bu emaili görmezden gelebilirsiniz.</p>
            </div>
            
            <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                <p style='color: #666; font-size: 12px;'>
                    Bu email DreamLock sisteminden otomatik olarak gönderilmiştir.<br>
                    Lütfen bu emaili yanıtlamayın.
                </p>
            </div>
        </div>
    </body>
    </html>";
}
?>








