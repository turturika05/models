<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка моделей для выбора
try {
	$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM models");
	$stmt->execute();
	$models = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных: " . $e->getMessage();
}

// Обработка формы после отправки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$photoshoots_name = $_POST['photoshoots_name'];
	$description = $_POST['description'];
	$date = $_POST['date'];
	$selected_models = $_POST['models']; // Массив выбранных моделей

	// Загрузка файлов для фотосессии
	$uploadDir = '../../uploads/photoshoot_images/';
	$uploadedFiles = [];

	// Перебираем загруженные файлы и сохраняем их
	foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
		$fileName = $_FILES['photos']['name'][$key];
		$uploadPath = $uploadDir . basename($fileName);

		if (move_uploaded_file($tmp_name, $uploadPath)) {
			$uploadedFiles[] = $uploadPath;
		} else {
			echo "Ошибка при загрузке файла $fileName";
		}
	}

	try {
		$pdo->beginTransaction();

		// Вставка данных в таблицу photoshoots
		$stmt_photoshoots = $pdo->prepare("INSERT INTO photoshoots (photoshoots_name, description, date) VALUES (?, ?, ?)");
		$stmt_photoshoots->execute([$photoshoots_name, $description, $date]);
		$photoshoot_id = $pdo->lastInsertId(); // Получаем ID вставленной фотосессии

		// Вставка данных в таблицу photoshoot_models
		$stmt_photoshoot_models = $pdo->prepare("INSERT INTO photoshoot_models (photoshoot_id, model_id) VALUES (?, ?)");
		foreach ($selected_models as $model_id) {
			$stmt_photoshoot_models->execute([$photoshoot_id, $model_id]);
		}

		// Вставка путей к загруженным фотографиям в таблицу photoshoot_photos
		$stmt_photos = $pdo->prepare("INSERT INTO photoshoot_photos (photoshoot_id, file_path) VALUES (?, ?)");
		foreach ($uploadedFiles as $file) {
			$stmt_photos->execute([$photoshoot_id, $file]);
		}

		$pdo->commit();

		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		$pdo->rollBack();
		echo "Ошибка при добавлении фотосессии: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить фотосессию</h3>
	<form action="create_photoshoot.php" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="photoshoots_name">Название фотосессии:</label>
			<input type="text" id="photoshoots_name" name="photoshoots_name" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="description">Описание:</label>
			<textarea id="description" name="description" class="form-control" rows="3"></textarea>
		</div>
		<div class="form-group">
			<label for="date">Дата:</label>
			<input type="date" id="date" name="date" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="models">Выберите моделей:</label>
			<select multiple id="models" name="models[]" class="form-control" required>
		  <?php foreach ($models as $model): ?>
						<option value="<?php echo $model['id']; ?>"><?php echo $model['first_name'] . ' ' . $model['last_name']; ?></option>
		  <?php endforeach; ?>
			</select>
		</div>
		<div class="form-group my-3">
			<label for="photos">Загрузить фотографии:</label>
			<input type="file" id="photos" name="photos[]" class="form-control-file" multiple required>
		</div>
		<button type="submit" class="btn btn-success">Добавить</button>
	</form>
</div>
</body>
</html>
