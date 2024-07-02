<?php
session_start();
require_once 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'] ?? '';
	$phone = $_POST['phone'] ?? '';
	$email = $_POST['email'] ?? '';
	$casting_id = $_POST['casting_id'] ?? '';
	$message = $_POST['message'] ?? '';

	if (isset($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
	} else {
		$user_id = null; // Если пользователь не авторизован
	}

	try {
		$stmt = $pdo->prepare("
            INSERT INTO casting_applications (name, phone, email, casting_id, message, user_id)
            VALUES (:name, :phone, :email, :casting_id, :message, :user_id)
        ");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':casting_id', $casting_id);
		$stmt->bindParam(':message', $message);
		$stmt->bindParam(':user_id', $user_id);

		if ($stmt->execute()) {
			$_SESSION['success_message'] = "Заявка успешно отправлена.";
			header('Location: casting.php');
			exit();
		} else {
			$_SESSION['error_message'] = "Ошибка при отправке заявки.";
			header('Location: casting.php');
			exit();
		}
	} catch (PDOException $e) {
		$_SESSION['error_message'] = "Ошибка базы данных: " . $e->getMessage();
		header('Location: casting.php');
		exit();
	}
} else {
	header('Location: casting.php');
	exit();
}
?>
