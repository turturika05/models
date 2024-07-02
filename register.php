<?php
// Подключение к базе данных
require_once 'database_connection.php';

session_start();

// Проверка, была ли отправлена форма регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Получение данных из формы
	$login = $_POST['reg-login'];
	$password = $_POST['reg-password'];

	// Хеширование пароля
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	try {
		// Проверка, существует ли пользователь с email
		$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$login]);

		if ($stmt->rowCount() > 0) {
			// Пользователь с таким email уже существует
			echo json_encode(['success' => false, 'message' => 'Пользователь с таким email уже существует.']);
			exit();
		} else {
			// Добавление нового пользователя в базу данных
			$stmt = $pdo->prepare("INSERT INTO users (email, password, registration_date, is_admin) VALUES (?, ?, NOW(), 0)");
			$stmt->execute([$login, $hashed_password]);

			// Регистрация успешна, сохранение данных пользователя в сессии
			$_SESSION['user_id'] = $pdo->lastInsertId();
			$_SESSION['is_admin'] = 0;

			// Ответ успешен
			echo json_encode(['success' => true]);
			exit();
		}
	} catch (PDOException $e) {
		// Вывод ошибки регистрации
		echo json_encode(['success' => false, 'message' => 'Ошибка регистрации: ' . $e->getMessage()]);
		exit();
	}
} else {
	// Если запрос не POST
	echo json_encode(['success' => false, 'message' => 'Неверный запрос.']);
	exit();
}
?>
