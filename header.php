<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<title>Главная</title>
</head>
<body>
<header>
	<div class="container">
		<div class="logo">
			<img src="img/logo.png" alt="Лого">
		</div>
		<nav class="menu">
			<ul>
				<li><a href="index.php">Главная</a></li>
				<li><a href="news.php">Новости</a></li>
				<li><a href="portfolio.php">Портфолио</a></li>
				<li><a href="casting.php">Кастинги</a></li>
				<li><a href="project.php">Проекты</a></li>
				<li><a href="contact.php">Контакты</a></li>
			</ul>
		</nav>
		<div class="auth">
		<?php if ($isLoggedIn): ?>
					<a href="profile.php" class="login" style="margin-right: 5px;">Профиль</a>
					<a href="logout.php" class="login">Выйти</a>
		<?php else: ?>
					<a href="#" class="register" id="openRegisterModalBtn">Зарегистрироваться</a>
					<button class="login" id="openLoginModalBtn">Войти</button>
		<?php endif; ?>
		</div>
	</div>
</header>
