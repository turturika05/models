<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Модельное агенство</title>
	<!-- Дополнительные стили, скрипты и другие мета-данные -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="">
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-1 border-bottom bg-dark">
		<div class="col-md-auto mb-0 mx-5 justify-content-center">
			<h4 class="d-inline-flex link-body-emphasis  text-decoration-none text-light">Модельное агенство</h4>
		</div>

		<ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
			<li><a href="../../../admin/users" class="nav-link px-2 text-light">Пользователи</a></li>
			<li><a href="../../../admin/posts" class="nav-link px-2 text-light">Посты</a></li>
			<li><a href="../../../admin/skills" class="nav-link px-2 text-light">Навыки</a></li>
			<li><a href="../../../admin/models" class="nav-link px-2 text-light">Модели</a></li>
			<li><a href="../../../admin/clients" class="nav-link px-2 text-light">Клиенты</a></li>
			<li><a href="../../../admin/contracts" class="nav-link px-2 text-light">Контракты</a></li>
			<li><a href="../../../admin/photoshoots" class="nav-link px-2 text-light">Фотосессии</a></li>
			<li><a href="../../../admin/castings" class="nav-link px-2 text-light">Кастинги</a></li>
		</ul>

		<div class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
		<?php if (!isset($_SESSION['user_id'])) : ?>
					<a href="../../../admin/login.php" class="btn btn-outline-light me-2  d-inline-block">Вход</a>
		<?php else : ?>
					<a href="../../../admin/logout.php" class="btn btn-secondary me-5 d-inline-block">Выйти</a>
		<?php endif; ?>
		</div>
	</header>
</div>
</body>
</html>
