<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

// Form verilerini al
$name    = isset($_POST['username']) ? trim($_POST['username']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone   = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basit doğrulama
if (!$name || !$email || !$phone || !$subject || !$message) {
    echo "⚠️ Lütfen tüm alanları doldurun.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Geçersiz e-posta adresi.";
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP ayarları
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'baglanti_mail'; // kendi Gmail adresin
    $mail->Password   = 'password';        // Gmail uygulama şifresi
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Gönderici ve alıcı
    $mail->setFrom('gonderici_mail', 'Web Sitesi');
    $mail->addAddress('alici_mail', 'Kendim'); // kendine gönder
    $mail->addReplyTo($email, $name); // Yanıtlar formu dolduran kişiye gitsin

    // Mesaj içeriği
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body    = "Ad: $name\nE-posta: $email\nTelefon: $phone\n\nMesaj:\n$message";

    // Mail gönder
    if ($mail->send()) {
        echo "✅ Mesajınız başarıyla gönderildi.";
    } else {
        echo "❌ Mail gönderilemedi. Lütfen daha sonra tekrar deneyin.";
    }

} catch (Exception $e) {
    // Hata loglanabilir: error_log($e->getMessage());
    echo "❌ Mail gönderilemedi. Lütfen daha sonra tekrar deneyin.";
}
