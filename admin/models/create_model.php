<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка всех навыков
try {
	$stmt = $pdo->query("SELECT * FROM skills");
	$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка навыков: " . $e->getMessage();
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
	$selected_skills = isset($_POST['skills']) ? $_POST['skills'] : [];

	try {
		// Начать транзакцию
		$pdo->beginTransaction();

		// Вставить модель в таблицу models
		$stmt = $pdo->prepare("INSERT INTO models (first_name, last_name, gender, birth_date, height, weight, hair_color, experience_level, active_contract) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute([$first_name, $last_name, $gender, $birth_date, $height, $weight, $hair_color, $experience_level, $active_contract]);
		$model_id = $pdo->lastInsertId();

		// Обработка загрузки фотографий
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

		// Вставка выбранных навыков в таблицу связей
		foreach ($selected_skills as $skill_id) {
			$stmt = $pdo->prepare("INSERT INTO model_skills (model_id, skill_id) VALUES (?, ?)");
			$stmt->execute([$model_id, $skill_id]);
		}

		// Завершить транзакцию
		$pdo->commit();
		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		$pdo->rollBack();
		echo "Ошибка при добавлении модели: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить модель</h3>
	<form action="create_model.php" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="first_name">Имя:</label>
			<input type="text" id="first_name" name="first_name" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="last_name">Фамилия:</label>
			<input type="text" id="last_name" name="last_name" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="gender">Пол:</label>
			<select id="gender" name="gender" class="form-control" required>
				<option value="Male">Мужской</option>
				<option value="Female">Женский</option>
				<option value="Other">Другой</option>
			</select>
		</div>
		<div class="form-group">
			<label for="birth_date">Дата рождения:</label>
			<input type="date" id="birth_date" name="birth_date" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="height">Рост (см):</label>
			<input type="number" id="height" name="height" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="weight">Вес (кг):</label>
			<input type="number" id="weight" name="weight" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="hair_color">Цвет волос:</label>
			<input type="text" id="hair_color" name="hair_color" class="form-control" required>
		</div>
		<div class="form-group mb-1">
			<label for="experience_level">Уровень опыта:</label>
			<select id="experience_level" name="experience_level" class="form-control" required>
				<option value="Beginner">Начинающий</option>
				<option value="Intermediate">Средний</option>
				<option value="Experienced">Опытный</option>
			</select>
		</div>
		<div class="form-group mb-1">
			<label for="active_contract">Активный контракт:</label>
			<input type="checkbox" id="active_contract" name="active_contract">
		</div>
		<div class="form-group mb-3">
			<label for="photos">Фотографии:</label>
			<input type="file" id="photos" name="photos[]" class="form-control" multiple>
		</div>
		<div class="form-group mb-3">
			<label for="skills">Навыки:</label>
		<?php foreach ($skills as $skill) : ?>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="skill_<?php echo $skill['id']; ?>" name="skills[]" value="<?php echo $skill['id']; ?>">
						<label class="form-check-label" for="skill_<?php echo $skill['id']; ?>"><?php echo $skill['skill_name']; ?></label>
					</div>
		<?php endforeach; ?>
		</div>
		<button type="submit" class="btn btn-success mb-5">Добавить</button>
	</form>
</div>
</body>
</html>
