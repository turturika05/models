<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if (!isset($_GET['photoshoot_id'])) {
	header('Location: index.php');
	exit();
}

$photoshoot_id = $_GET['photoshoot_id'];

// Получение данных фотосессии и списка моделей
try {
	$stmt_photoshoot = $pdo->prepare("SELECT * FROM photoshoots WHERE id = ?");
	$stmt_photoshoot->execute([$photoshoot_id]);
	$photoshoot = $stmt_photoshoot->fetch(PDO::FETCH_ASSOC);

	if (!$photoshoot) {
		echo "Фотосессия не найдена.";
		exit();
	}

	// Получение списка моделей для выбора
	$stmt_models = $pdo->prepare("SELECT id, first_name, last_name FROM models");
	$stmt_models->execute();
	$models = $stmt_models->fetchAll(PDO::FETCH_ASSOC);

	// Получение списка моделей, связанных с текущей фотосессией
	$stmt_selected_models = $pdo->prepare("SELECT model_id FROM photoshoot_models WHERE photoshoot_id = ?");
	$stmt_selected_models->execute([$photoshoot_id]);
	$selected_models = $stmt_selected_models->fetchAll(PDO::FETCH_COLUMN);

	// Получение списка текущих фотографий для фотосессии
	$stmt_photos = $pdo->prepare("SELECT id, file_path FROM photoshoot_photos WHERE photoshoot_id = ?");
	$stmt_photos->execute([$photoshoot_id]);
	$current_photos = $stmt_photos->fetchAll(PDO::FETCH_ASSOC);

	// Обработка удаления фотографий
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_photos'])) {
		$photosToDelete = $_POST['delete_photos'];

		foreach ($photosToDelete as $photoId) {
			// Получаем путь к файлу из базы данных
			$stmt_photo_path = $pdo->prepare("SELECT file_path FROM photoshoot_photos WHERE id = ?");
			$stmt_photo_path->execute([$photoId]);
			$photoPath = $stmt_photo_path->fetchColumn();

			// Удаляем запись из базы данных
			$stmt_delete_photo = $pdo->prepare("DELETE FROM photoshoot_photos WHERE id = ?");
			$stmt_delete_photo->execute([$photoId]);

			// Удаляем файл фотографии
			if (file_exists($photoPath)) {
				unlink($photoPath);
			}
		}

		// Перенаправление после удаления
		header('Location: edit_photoshoot.php?photoshoot_id=' . $photoshoot_id);
		exit();
	}

} catch (PDOException $e) {
	echo "Ошибка при получении данных: " . $e->getMessage();
}

// Обработка формы после отправки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$photoshoots_name = $_POST['photoshoots_name'];
	$description = $_POST['description'];
	$date = $_POST['date'];
	$selected_models = $_POST['models']; // Массив выбранных моделей

	// Обновление данных фотосессии и связанных моделей
	try {
		$pdo->beginTransaction();

		// Обновление данных в таблице photoshoots
		$stmt_update_photoshoots = $pdo->prepare("UPDATE photoshoots SET photoshoots_name = ?, description = ?, date = ? WHERE id = ?");
		$stmt_update_photoshoots->execute([$photoshoots_name, $description, $date, $photoshoot_id]);

		// Удаление старых связей photoshoot_models
		$stmt_delete_photoshoot_models = $pdo->prepare("DELETE FROM photoshoot_models WHERE photoshoot_id = ?");
		$stmt_delete_photoshoot_models->execute([$photoshoot_id]);

		// Вставка новых связей photoshoot_models
		$stmt_insert_photoshoot_models = $pdo->prepare("INSERT INTO photoshoot_models (photoshoot_id, model_id) VALUES (?, ?)");
		foreach ($selected_models as $model_id) {
			$stmt_insert_photoshoot_models->execute([$photoshoot_id, $model_id]);
		}

		// Загрузка новых фотографий (если есть)
		if (!empty(array_filter($_FILES['photos']['name']))) {
			$uploadDir = '../photoshoot_images/';
			$uploadedFiles = [];

			foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
				$fileName = $_FILES['photos']['name'][$key];
				$uploadPath = $uploadDir . basename($fileName);

				if (move_uploaded_file($tmp_name, $uploadPath)) {
					$uploadedFiles[] = $uploadPath;
				} else {
					echo "Ошибка при загрузке файла $fileName";
				}
			}

			// Вставка путей к загруженным фотографиям в таблицу photoshoot_photos
			$stmt_insert_photos = $pdo->prepare("INSERT INTO photoshoot_photos (photoshoot_id, file_path) VALUES (?, ?)");
			foreach ($uploadedFiles as $file) {
				$stmt_insert_photos->execute([$photoshoot_id, $file]);
			}
		}

		$pdo->commit();

		header('Location: index.php?message=edit_success');
	} catch (PDOException $e) {
		$pdo->rollBack();
		echo "Ошибка при обновлении фотосессии: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать фотосессию</h3>
	<form action="edit_photoshoot.php?photoshoot_id=<?php echo $photoshoot_id; ?>" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="photoshoots_name">Название фотосессии:</label>
			<input type="text" id="photoshoots_name" name="photoshoots_name" class="form-control" value="<?php echo $photoshoot['photoshoots_name']; ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="description">Описание:</label>
			<textarea id="description" name="description" class="form-control" rows="3"><?php echo $photoshoot['description']; ?></textarea>
		</div>
		<div class="form-group my-3">
			<label for="date">Дата:</label>
			<input type="date" id="date" name="date" class="form-control" value="<?php echo $photoshoot['date']; ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="models">Выберите моделей:</label>
			<select multiple id="models" name="models[]" class="form-control" required>
		  <?php foreach ($models as $model): ?>
						<option value="<?php echo $model['id']; ?>" <?php if (in_array($model['id'], $selected_models)) echo 'selected'; ?>>
				<?php echo $model['first_name'] . ' ' . $model['last_name']; ?>
						</option>
		  <?php endforeach; ?>
			</select>
		</div>

		<div class="form-group my-3">
			<label>Текущие фотографии:</label><br>
		<?php foreach ($current_photos as $photo): ?>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="delete_photos[]" value="<?php echo $photo['id']; ?>">
						<label class="form-check-label">
							<img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="Фото фотосессии" class="img-thumbnail" style="max-width: 200px; max-height: 200px; margin-right: 10px;">
						</label>
					</div>
		<?php endforeach; ?>
		</div>

		<div class="form-group my-3">
			<label for="photos">Загрузить новые фотографии:</label>
			<input type="file" id="photos" name="photos[]" class="form-control-file" multiple>
		</div>

		<button type="submit" class="btn btn-success mb-5">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
