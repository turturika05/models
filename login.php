<?php
session_start();

// Подключение к базе данных
require_once 'database_connection.php';

// Проверка, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
	// Пользователь уже авторизован, перенаправляем на домашнюю страницу
	header("Location: index.php");
	exit();
}

// Проверка, была ли отправлена форма авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Получение данных из формы
	$login = $_POST['login'];
	$password = $_POST['password'];
	$recaptchaResponse = $_POST['g-recaptcha-response'];

	// Проверка reCAPTCHA
	$secretKey = '6Lftzf4pAAAAAKUiht9zDHcFVEUN2ScQg1LC_xXL';
	$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
	$response = file_get_contents($recaptchaUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
	$responseKeys = json_decode($response, true);

	if ($responseKeys["success"]) {
		try {
			// Поиск пользователя в базе данных по email
			$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
			$stmt->execute([$login]);

			if ($stmt->rowCount() > 0) {
				$user = $stmt->fetch();

				// Получаем хешированный пароль из базы данных
				$hashed_password = $user['password'];

				// Проверяем пароль
				if (password_verify($password, $hashed_password)) {
					// Авторизация успешна, сохранение данных пользователя в сессии
					$_SESSION['user_id'] = $user['user_id'];
					$_SESSION['is_admin'] = $user['is_admin'];

					// Перенаправление на домашнюю страницу после успешной авторизации
					echo json_encode(['success' => true]);
					exit();
				} else {
					// Неверный пароль
					echo json_encode(['success' => false, 'message' => 'Неверный пароль.']);
					exit();
				}
			} else {
				// Пользователь с указанным email не найден
				echo json_encode(['success' => false, 'message' => 'Пользователь с указанным email не найден.']);
				exit();
			}
		} catch (PDOException $e) {
			// Вывод ошибки авторизации
			echo json_encode(['success' => false, 'message' => 'Ошибка авторизации: ' . $e->getMessage()]);
			exit();
		}
	} else {
		// Ошибка проверки reCAPTCHA
		echo json_encode(['success' => false, 'message' => 'Ошибка проверки reCAPTCHA.']);
		exit();
	}
} else {
	// Если запрос не POST
	echo json_encode(['success' => false, 'message' => 'Неверный запрос.']);
	exit();
}
?>
