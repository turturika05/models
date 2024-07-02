<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = $_POST['title'];
	$main_text = $_POST['main_text'];
	$category = $_POST['category'];
	$user_id = $_SESSION['user_id'];

	// Обработка загрузки изображения
	$image_path = null;
	if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
		$image_name = $_FILES['image']['name'];
		$image_tmp_name = $_FILES['image']['tmp_name'];
		$upload_dir = '../../uploads/posts/';
		$image_path = $upload_dir . $image_name;

		if (move_uploaded_file($image_tmp_name, $image_path)) {
			// Файл успешно загружен
		} else {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка при загрузке изображения.</div>';
			exit();
		}
	}

	try {
		$stmt = $pdo->prepare("INSERT INTO posts (title, image_path, main_text, category, user_id) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$title, $image_path, $main_text, $category, $user_id]);
		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		echo "Ошибка при добавлении поста: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить новость</h3>
	<form action="create_post.php" method="post" enctype="multipart/form-data">
		<div class="form-group my-3">
			<label for="title">Заголовок:</label>
			<input type="text" id="title" name="title" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="main_text">Основной текст:</label>
			<textarea id="main_text" name="main_text" class="form-control" rows="5" required></textarea>
		</div>
		<div class="form-group my-3">
			<label for="category">Категория:</label>
			<input type="text" id="category" name="category" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="image">Изображение:</label>
			<input type="file" id="image" name="image" class="form-control-file" accept="image/*" required>
		</div>
		<button type="submit" class="btn btn-success mb-5">Добавить</button>
	</form>
</div>
</body>
</html>
