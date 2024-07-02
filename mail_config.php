<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'app/PHPMailer/Exception.php';
require 'app/PHPMailer/PHPMailer.php';
require 'app/PHPMailer/SMTP.php';

function setupMailer() {
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		$mail->CharSet = PHPMailer::CHARSET_UTF8;
		$mail->Host = 'ssl://smtp.mail.ru';
		$mail->SMTPAuth = true;
		$mail->Username = 'turturika05@mail.ru';
		$mail->Password = 'Ht47YZpxby7uc7sgDvVt';
		$mail->Port = 465;

		$mail->setFrom('turturika05@mail.ru', 'Модельное агенство');
		return $mail;
	} catch (Exception $e) {
		throw new Exception('Ошибка при настройке PHPMailer: ' . $e->getMessage());
	}
}
?>
