<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Значения по умолчанию для фильтров (можно настроить по вашему выбору)
$filterFirstName = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$filterLastName = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$filterGender = isset($_GET['gender']) ? $_GET['gender'] : '';
$filterHairColor = isset($_GET['hair_color']) ? $_GET['hair_color'] : '';
$filterExperience = isset($_GET['experience']) ? $_GET['experience'] : '';
$filterMinAge = isset($_GET['min_age']) ? $_GET['min_age'] : '';
$filterMaxAge = isset($_GET['max_age']) ? $_GET['max_age'] : '';
$filterSkills = isset($_GET['skills']) ? $_GET['skills'] : [];

// Подготовка SQL-запроса с возможными условиями фильтрации
$sql = "SELECT DISTINCT m.*
        FROM models m
        LEFT JOIN model_skills ms ON m.id = ms.model_id
        WHERE 1";
$params = [];

if (!empty($filterFirstName)) {
	$sql .= " AND m.first_name LIKE ?";
	$params[] = "%$filterFirstName%";
}

if (!empty($filterLastName)) {
	$sql .= " AND m.last_name LIKE ?";
	$params[] = "%$filterLastName%";
}

if (!empty($filterGender)) {
	$sql .= " AND m.gender = ?";
	$params[] = $filterGender;
}

if (!empty($filterHairColor)) {
	$sql .= " AND m.hair_color = ?";
	$params[] = $filterHairColor;
}

if (!empty($filterExperience)) {
	$sql .= " AND m.experience_level = ?";
	$params[] = $filterExperience;
}

if (!empty($filterMinAge)) {
	$sql .= " AND TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) >= ?";
	$params[] = $filterMinAge;
}

if (!empty($filterMaxAge)) {
	$sql .= " AND TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) <= ?";
	$params[] = $filterMaxAge;
}

// Фильтрация по навыкам
if (!empty($filterSkills)) {
	$placeholders = implode(',', array_fill(0, count($filterSkills), '?'));
	$sql .= " AND ms.skill_id IN ($placeholders)";
	$params = array_merge($params, $filterSkills);
}

try {
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$models = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка моделей: " . $e->getMessage();
}

// Получение навыков для каждой модели
$skills_map = [];
try {
	foreach ($models as $model) {
		$stmt = $pdo->prepare("SELECT s.skill_name FROM model_skills ms JOIN skills s ON ms.skill_id = s.id WHERE ms.model_id = ?");
		$stmt->execute([$model['id']]);
		$skills_map[$model['id']] = $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
} catch (PDOException $e) {
	echo "Ошибка при получении навыков: " . $e->getMessage();
}

$gender_map = [
	'Male' => 'Мужской',
	'Female' => 'Женский',
	'Other' => 'Другой'
];

$experience_map = [
	'Beginner' => 'Начинающий',
	'Intermediate' => 'Средний',
	'Experienced' => 'Опытный'
];
?>

<body class="bg-dark text-light">
<div class="container">
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'create_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Модель успешно добавлена.</div>';
		}

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Модель успешно обновлена.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Модель успешно удалена.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список моделей</h3>
	<a href="create_model.php" class="btn btn-success mb-3">Добавить модель</a>

	<!-- Форма для фильтрации -->
	<form method="GET" action="">
		<div class="row">
			<div class="col-md-2">
				<input type="text" name="first_name" class="form-control mb-2" placeholder="Имя" value="<?= htmlspecialchars($filterFirstName) ?>">
			</div>
			<div class="col-md-2">
				<input type="text" name="last_name" class="form-control mb-2" placeholder="Фамилия" value="<?= htmlspecialchars($filterLastName) ?>">
			</div>
			<div class="col-md-2">
				<select name="gender" class="form-control mb-2">
					<option value="">Выберите пол</option>
			<?php foreach ($gender_map as $key => $value): ?>
							<option value="<?= $key ?>" <?= ($filterGender == $key) ? 'selected' : '' ?>><?= $value ?></option>
			<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<input type="text" name="hair_color" class="form-control mb-2" placeholder="Цвет волос" value="<?= htmlspecialchars($filterHairColor) ?>">
			</div>
			<div class="col-md-2">
				<select name="experience" class="form-control mb-2">
					<option value="">Выберите опыт</option>
			<?php foreach ($experience_map as $key => $value): ?>
							<option value="<?= $key ?>" <?= ($filterExperience == $key) ? 'selected' : '' ?>><?= $value ?></option>
			<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-1">
				<input type="number" name="min_age" class="form-control mb-2" placeholder="От" value="<?= htmlspecialchars($filterMinAge) ?>">
			</div>
			<div class="col-md-1">
				<input type="number" name="max_age" class="form-control mb-2" placeholder="До" value="<?= htmlspecialchars($filterMaxAge) ?>">
			</div>
		</div>

		<!-- Выбор навыков для фильтрации -->
		<div class="row mb-3">
			<div class="col">
				<label class="form-label">Выберите навыки:</label>
				<select name="skills[]" multiple class="form-control">
			<?php
			$stmt = $pdo->query("SELECT * FROM skills");
			$allSkills = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($allSkills as $skill): ?>
							<option value="<?= $skill['id'] ?>" <?= in_array($skill['id'], $filterSkills) ? 'selected' : '' ?>>
				  <?= htmlspecialchars($skill['skill_name']) ?>
							</option>
			<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="text-end">
			<button type="submit" class="btn btn-primary">Применить фильтр</button>
		</div>
	</form>

	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Имя</th>
			<th>Фамилия</th>
			<th>Пол</th>
			<th>Дата рождения</th>
			<th>Рост</th>
			<th>Вес</th>
			<th>Цвет волос</th>
			<th>Уровень опыта</th>
			<th>Активный контракт</th>
			<th>Навыки</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($models as $model): ?>
			<tr>
				<td><?= htmlspecialchars($model['first_name']) ?></td>
				<td><?= htmlspecialchars($model['last_name']) ?></td>
				<td><?= $gender_map[$model['gender']] ?></td>
				<td><?= htmlspecialchars($model['birth_date']) ?></td>
				<td><?= htmlspecialchars($model['height']) ?> см</td>
				<td><?= htmlspecialchars($model['weight']) ?> кг</td>
				<td><?= htmlspecialchars($model['hair_color']) ?></td>
				<td><?= $experience_map[$model['experience_level']] ?></td>
				<td><?= $model['active_contract'] ? 'Да' : 'Нет' ?></td>
				<td><?= implode(', ', $skills_map[$model['id']]) ?></td>
				<td>
					<div class="btn-group" role="group">
						<!-- Форма редактирования модели -->
						<form method="GET" action="edit_model.php" class="mx-2">
							<input type="hidden" name="model_id" value="<?= $model['id'] ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>

						<!-- Форма удаления модели -->
						<form method="POST" action="delete_model.php" onsubmit="return confirm('Вы уверены, что хотите удалить эту модель?');">
							<input type="hidden" name="model_id" value="<?= $model['id'] ?>">
							<input type="submit" class="btn btn-danger" value="Удалить">
						</form>
					</div>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</div>
</body>
</html>
