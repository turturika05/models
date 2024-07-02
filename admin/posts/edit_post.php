<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Проверяем, был ли отправлен POST-запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Получаем данные из POST-запроса
	$post_id = $_POST['post_id'];
	$title = $_POST['title'];
	$main_text = $_POST['main_text'];
	$category = $_POST['category'];

	// Получаем текущий путь до изображения поста
	$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
	$stmt->execute([$post_id]);
	$post = $stmt->fetch(PDO::FETCH_ASSOC);
	$current_image_path = $post['image_path'];

	try {
		// Проверяем, было ли загружено новое изображение
		if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
			// Обрабатываем загрузку нового изображения
			$image_name = $_FILES['image']['name'];
			$image_tmp_name = $_FILES['image']['tmp_name'];
			$upload_dir = '../../uploads/posts/';
			$image_path = $upload_dir . $image_name;

			if (move_uploaded_file($image_tmp_name, $image_path)) {
				// Успешно загружено новое изображение, обновляем путь в базе данных
				$stmt = $pdo->prepare("UPDATE posts SET title = ?, main_text = ?, category = ?, image_path = ? WHERE post_id = ?");
				$stmt->execute([$title, $main_text, $category, $image_path, $post_id]);

				// Удаляем старое изображение, если оно было изменено
				if ($current_image_path && $current_image_path !== $image_path) {
					unlink($current_image_path);
				}
			} else {
				echo '<div class="alert alert-danger mt-3" role="alert">Ошибка при загрузке нового изображения.</div>';
				exit();
			}
		} else {
			// Если новое изображение не было загружено, обновляем данные без изменения изображения
			$stmt = $pdo->prepare("UPDATE posts SET title = ?, main_text = ?, category = ? WHERE post_id = ?");
			$stmt->execute([$title, $main_text, $category, $post_id]);
		}

		// Перенаправляем пользователя на страницу списка постов с сообщением об успешном редактировании
		header('Location: index.php?message=edit_success');
		exit(); // Прерываем выполнение скрипта
	} catch (PDOException $e) {
		// В случае ошибки выводим сообщение
		echo "Ошибка при обновлении поста: " . $e->getMessage();
	}
}

// Проверяем, был ли передан параметр post_id через GET-запрос
if (isset($_GET['post_id'])) {
	// Получаем post_id из GET-запроса
	$post_id = $_GET['post_id'];

	try {
		// Подготавливаем SQL-запрос для получения данных о посте по его ID
		$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
		// Выполняем SQL-запрос с передачей параметров
		$stmt->execute([$post_id]);
		// Извлекаем данные поста из результата запроса
		$post = $stmt->fetch(PDO::FETCH_ASSOC);

		// Проверяем, найден ли пост
		if (!$post) {
			// Если пост не найден, выводим сообщение об ошибке
			echo '<div class="alert alert-danger mt-3" role="alert">Пост не найден.</div>';
			exit(); // Прерываем выполнение скрипта
		}
	} catch (PDOException $e) {
		// В случае ошибки выводим сообщение
		echo "Ошибка при получении данных о посте: " . $e->getMessage();
		exit(); // Прерываем выполнение скрипта
	}
} else {
	// Если post_id не был передан, выводим сообщение об ошибке
	echo '<div class="alert alert-danger mt-3" role="alert">Не указан идентификатор поста.</div>';
	exit(); // Прерываем выполнение скрипта
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать новость</h3>
	<form action="edit_post.php" method="post" enctype="multipart/form-data">
		<!-- Скрытое поле для передачи post_id -->
		<input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
		<div class="form-group my-3">
			<label for="title">Заголовок:</label>
			<input type="text" id="title" name="title" class="form-control" value="<?php echo $post['title']; ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="main_text">Основной текст:</label>
			<textarea id="main_text" name="main_text" class="form-control" rows="5" required><?php echo $post['main_text']; ?></textarea>
		</div>
		<div class="form-group my-3">
			<label for="category">Категория:</label>
			<input type="text" id="category" name="category" class="form-control" value="<?php echo $post['category']; ?>" required>
		</div>
		<!-- Отображение текущего изображения -->
	  <?php if ($post['image_path']) : ?>
				<div class="form-group my-3">
					<label>Текущее изображение:</label><br>
					<img src="<?php echo $post['image_path']; ?>" class="img-thumbnail" style="max-width: 200px;">
				</div>
	  <?php endif; ?>
		<div class="form-group my-3">
			<label for="image">Изображение:</label>
			<input type="file" id="image" name="image" class="form-control-file" accept="image/*">
		</div>
		<button type="submit" class="btn btn-primary mb-5">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
