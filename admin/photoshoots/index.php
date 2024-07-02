<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка моделей для выпадающего списка
try {
	$stmt = $pdo->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM models");
	$stmt->execute();
	$models = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка моделей: " . $e->getMessage();
}

// Получение списка фотосессий с именами моделей
try {
	$sql = "
        SELECT ps.*, GROUP_CONCAT(m.first_name, ' ', m.last_name ORDER BY m.first_name SEPARATOR '<br> ') AS models
        FROM photoshoots ps
        LEFT JOIN photoshoot_models pm ON ps.id = pm.photoshoot_id
        LEFT JOIN models m ON pm.model_id = m.id";

	$params = [];
	if (isset($_GET['model_id']) && $_GET['model_id'] !== '') {
		$sql .= " WHERE pm.model_id = ?";
		$params[] = $_GET['model_id'];
	}

	$sql .= " GROUP BY ps.id";

	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$photoshoots = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка фотосессий: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'create_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Фотосессия успешно добавлена.</div>';
		}

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Фотосессия успешно обновлена.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Фотосессия успешно удалена.</div>';
		}

		if ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления фотосессии.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список фотосессий</h3>
	<a href="create_photoshoot.php" class="btn btn-success mb-3">Добавить фотосессию</a>

	<!-- Форма для фильтрации по модели -->
	<form method="GET" action="">
		<div class="row align-items-end">
			<div class="col-md-6">
				<select name="model_id" class="form-control mb-2">
					<option value="">Выберите модель</option>
			<?php foreach ($models as $model): ?>
							<option value="<?= $model['id'] ?>" <?= ($_GET['model_id'] ?? '') == $model['id'] ? 'selected' : '' ?>>
				  <?= htmlspecialchars($model['full_name']) ?>
							</option>
			<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-primary mb-2">Применить фильтр</button>
			</div>
		</div>
	</form>

	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>ID</th>
			<th>Название</th>
			<th>Описание</th>
			<th>Дата</th>
			<th>Модели</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($photoshoots as $photoshoot): ?>
			<tr>
				<td><?= $photoshoot['id'] ?></td>
				<td><?= $photoshoot['photoshoots_name'] ?></td>
				<td><?= $photoshoot['description'] ?></td>
				<td><?= $photoshoot['date'] ?></td>
				<td><?= $photoshoot['models'] ?></td>
				<td>
					<div class="btn-group" role="group">
						<!-- Форма редактирования фотосессии -->
						<form method="GET" action="edit_photoshoot.php" class="mx-2">
							<input type="hidden" name="photoshoot_id" value="<?= $photoshoot['id'] ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>

						<!-- Форма удаления фотосессии -->
						<form method="POST" action="delete_photoshoot.php" onsubmit="return confirm('Вы уверены, что хотите удалить эту фотосессию?');">
							<input type="hidden" name="photoshoot_id" value="<?= $photoshoot['id'] ?>">
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
