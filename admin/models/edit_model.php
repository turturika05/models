<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if (!isset($_GET['model_id'])) {
	header('Location: index.php');
	exit();
}

$model_id = $_GET['model_id'];

// Получение данных модели
try {
	$stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
	$stmt->execute([$model_id]);
	$model = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$model) {
		echo "Модель не найдена.";
		exit();
	}

	// Получение списка всех навыков
	$stmt = $pdo->query("SELECT * FROM skills");
	$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Получение списка навыков модели
	$stmt = $pdo->prepare("SELECT skill_id FROM model_skills WHERE model_id = ?");
	$stmt->execute([$model_id]);
	$model_skills = $stmt->fetchAll(PDO::FETCH_COLUMN);

	// Получение фотографий модели
	$stmt = $pdo->prepare("SELECT * FROM model_photos WHERE model_id = ?");
	$stmt->execute([$model_id]);
	$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных модели: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$gender = $_POST['gender'];
	$birth_date = $_POST['birth_date'];
	$height = $_POST['height'];
	$weight = $_POST['weight'];
	$hair_color = $_POST['hair_color'];
	$experience_level = $_POST['experience_level'];
	$active_contract = isset($_POST['active_contract']) ? 1 : 0;

	try {
		// Начать транзакцию
		$pdo->beginTransaction();

		// Обновление данных модели
		$stmt = $pdo->prepare("UPDATE models SET first_name = ?, last_name = ?, gender = ?, birth_date = ?, height = ?, weight = ?, hair_color = ?, experience_level = ?, active_contract = ? WHERE id = ?");
		$stmt->execute([$first_name, $last_name, $gender, $birth_date, $height, $weight, $hair_color, $experience_level, $active_contract, $model_id]);

		// Удаление старых навыков модели
		$stmt = $pdo->prepare("DELETE FROM model_skills WHERE model_id = ?");
		$stmt->execute([$model_id]);

		// Добавление новых навыков модели
		if (isset($_POST['skills'])) {
			foreach ($_POST['skills'] as $skill_id) {
				$stmt = $pdo->prepare("INSERT INTO model_skills (model_id, skill_id) VALUES (?, ?)");
				$stmt->execute([$model_id, $skill_id]);
			}
		}

		// Обработка удаления старых фотографий
		if (isset($_POST['delete_photos'])) {
			foreach ($_POST['delete_photos'] as $photo_id) {
				$stmt = $pdo->prepare("SELECT file_path FROM model_photos WHERE id = ?");
				$stmt->execute([$photo_id]);
				$photo = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($photo) {
					unlink($photo['file_path']); // Удаление файла из диска
					$stmt = $pdo->prepare("DELETE FROM model_photos WHERE id = ?");
					$stmt->execute([$photo_id]);
				}
			}
		}

		// Обработка загрузки новых фотографий
		if (!empty($_FILES['photos']['name'][0])) {
			$upload_directory = '../../uploads/';
			foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
				$file_name = basename($_FILES['photos']['name'][$key]);
				$file_path = $upload_directory . $file_name;
				if (move_uploaded_file($tmp_name, $file_path)) {
					$stmt = $pdo->prepare("INSERT INTO model_photos (model_id, file_path) VALUES (?, ?)");
					$stmt->execute([$model_id, $file_path]);
				}
			}
		}

		// Завершить транзакцию
		$pdo->commit();
		header('Location: index.php?message=edit_success');
		exit(); // Важно завершить скрипт после перенаправления
	} catch (PDOException $e) {
		$pdo->rollBack();
		echo "Ошибка при обновлении модели: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать модель</h3>
	<form action="edit_model.php?model_id=<?php echo $model_id; ?>" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="first_name">Имя:</label>
			<input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $model['first_name']; ?>" required>
		</div>
		<div class="form-group">
			<label for="last_name">Фамилия:</label>
			<input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $model['last_name']; ?>" required>
		</div>
		<div class="form-group">
			<label for="gender">Пол:</label>
			<select id="gender" name="gender" class="form-control" required>
				<option value="Male" <?php if ($model['gender'] === 'Male') echo 'selected'; ?>>Мужской</option>
				<option value="Female" <?php if ($model['gender'] === 'Female') echo 'selected'; ?>>Женский</option>
				<option value="Other" <?php if ($model['gender'] === 'Other') echo 'selected'; ?>>Другой</option>
			</select>
		</div>
		<div class="form-group">
			<label for="birth_date">Дата рождения:</label>
			<input type="date" id="birth_date" name="birth_date" class="form-control" value="<?php echo $model['birth_date']; ?>" required>
		</div>
		<div class="form-group">
			<label for="height">Рост (см):</label>
			<input type="number" id="height" name="height" class="form-control" value="<?php echo $model['height']; ?>" required>
		</div>
		<div class="form-group">
			<label for="weight">Вес (кг):</label>
			<input type="number" id="weight" name="weight" class="form-control" value="<?php echo $model['weight']; ?>" required>
		</div>
		<div class="form-group">
			<label for="hair_color">Цвет волос:</label>
			<input type="text" id="hair_color" name="hair_color" class="form-control" value="<?php echo $model['hair_color']; ?>" required>
		</div>
		<div class="form-group mb-1">
			<label for="experience_level">Уровень опыта:</label>
			<select id="experience_level" name="experience_level" class="form-control" required>
				<option value="Beginner" <?php if ($model['experience_level'] === 'Beginner') echo 'selected'; ?>>Начинающий</option>
				<option value="Intermediate" <?php if ($model['experience_level'] === 'Intermediate') echo 'selected'; ?>>Средний</option>
				<option value="Experienced" <?php if ($model['experience_level'] === 'Experienced') echo 'selected'; ?>>Опытный</option>
			</select>
		</div>
		<div class="form-group mb-1">
			<label for="active_contract">Активный контракт:</label>
			<input type="checkbox" id="active_contract" name="active_contract" <?php if ($model['active_contract']) echo 'checked'; ?>>
		</div>
		<div class="form-group">
			<label>Текущие фотографии:</label>
			<div class="mb-1">
		  <?php foreach ($photos as $photo) : ?>
						<div class="d-inline-block m-2">
							<img src="<?php echo $photo['file_path']; ?>" alt="photo" class="img-thumbnail" width="100">
							<br>
							<input type="checkbox" name="delete_photos[]" value="<?php echo $photo['id']; ?>"> Удалить
						</div>
		  <?php endforeach; ?>
			</div>
		</div>
		<div class="form-group mb-3">
			<label for="photos">Новые фотографии:</label>
			<input type="file" id="photos" name="photos[]" class="form-control" multiple>
		</div>
		<div class="form-group mb-3">
			<label for="skills">Навыки:</label>
			<div>
		  <?php foreach ($skills as $skill) : ?>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="skills[]" id="skill_<?php echo $skill['id']; ?>" value="<?php echo $skill['id']; ?>" <?php if (in_array($skill['id'], $model_skills)) echo 'checked'; ?>>
							<label class="form-check-label" for="skill_<?php echo $skill['id']; ?>"><?php echo $skill['skill_name']; ?></label>
						</div>
		  <?php endforeach; ?>
			</div>
		</div>
		<button type="submit" class="btn btn-success mb-5">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
