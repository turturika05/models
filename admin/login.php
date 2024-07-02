<?php
session_start();
// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
	// Пользователь не авторизован, перенаправляем на страницу входа
	header("Location: users/index.php");
	exit();
}

include 'header.php';

// Подключение к базе данных
require_once 'database_connection.php';

// Проверка, была ли отправлена форма входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Получение данных из формы
	$email = $_POST['email'];
	$password = $_POST['password'];

	try {
		// Поиск пользователя в базе данных
		$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$email]);

		if ($stmt->rowCount() > 0) {
			$user = $stmt->fetch();

			// Проверка, является ли пользователь администратором
			if ($user['is_admin'] == 1) {
				// Проверка пароля
				if (password_verify($password, $user['password'])) {
					// Авторизация успешна, сохранение данных пользователя в сессии
					$_SESSION['user_id'] = $user['user_id'];
					$_SESSION['email'] = $user['email'];

					// Перенаправление на домашнюю страницу после успешного входа
					echo '<meta http-equiv="refresh" content="0; URL=../../admin/models/index.php">';
					exit();
				} else {
					// Неверный пароль
					echo '<div class="container"><div class="alert alert-danger" role="alert">Неверный пароль.</div></div>';
				}
			} else {
				// Пользователь не является администратором
				echo '<div class="container"><div class="alert alert-danger" role="alert">У вас нет прав администратора.</div></div>';
			}
		} else {
			// Пользователь не найден
			echo '<div class="container"><div class="alert alert-danger" role="alert">Пользователь не найден.</div></div>';
		}
	} catch (PDOException $e) {
		// Вывод ошибки входа
		echo '<div class="alert alert-danger" role="alert">Ошибка входа: ' . $e->getMessage() . '</div>';
	}
}
?>

<body class="bg-dark">
<div class="container d-flex justify-content-center mt-5">
	<div class="col-md-4">
		<h2 class="text-light text-center">Авторизация</h2>
		<form method="POST" action="login.php">
			<div class="form-group">
				<label for="email" class="text-light">Email:</label>
				<input type="email" class="form-control" name="email" id="email" required>
			</div>
			<div class="form-group mb-3">
				<label for="password" class="text-light">Пароль:</label>
				<input type="password" class="form-control" name="password" id="password" required>
			</div>
			<button type="submit" class="btn btn-danger btn-block">Войти</button>
		</form>
	</div>
</div>
</body>
