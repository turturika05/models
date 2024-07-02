<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка клиентов
try {
	$stmt = $pdo->prepare("SELECT * FROM clients");
	$stmt->execute();
	$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка клиентов: " . $e->getMessage();
}

// Получение ID выбранного клиента (если выбран)
$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : null;

// Получение списка моделей для выбранного клиента
$models = [];
if ($client_id) {
	try {
		$stmt = $pdo->prepare("
            SELECT m.*
            FROM models m
            INNER JOIN contracts c ON m.id = c.model_id
            WHERE c.client_id = ?
        ");
		$stmt->execute([$client_id]);
		$models = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		echo "Ошибка при получении списка моделей для клиента: " . $e->getMessage();
	}
}

// Массивы для перевода значений пола и уровня опыта
$genders = [
	'Male' => 'Мужской',
	'Female' => 'Женский',
	'Other' => 'Другой'
];

$experience_levels = [
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
			echo '<div class="alert alert-success mt-3" role="alert">Клиент успешно добавлен.</div>';
		}

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Клиент успешно обновлен.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Клиент успешно удален.</div>';
		}

		if ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления клиента.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список клиентов</h3>
	<a href="create_client.php" class="btn btn-success mb-3">Добавить клиента</a>
	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Имя</th>
			<th>Контактная информация</th>
			<th>Адрес</th>
			<th>Телефон</th>
			<th>Email</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($clients as $client): ?>
			<tr>
				<td><?php echo $client['name']; ?></td>
				<td><?php echo $client['contact_info']; ?></td>
				<td><?php echo $client['address']; ?></td>
				<td><?php echo $client['phone']; ?></td>
				<td><?php echo $client['email']; ?></td>
				<td>
					<div class="btn-group" role="group">
						<!-- Форма редактирования клиента -->
						<form method="GET" action="edit_client.php" class="mx-2">
							<input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>

						<!-- Форма удаления клиента -->
						<form method="POST" action="delete_client.php" onsubmit="return confirm('Вы уверены, что хотите удалить этого клиента?');">
							<input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
							<input type="submit" class="btn btn-danger" value="Удалить">
						</form>

						<!-- Форма просмотра моделей клиента -->
						<form method="GET" action="" class="mx-2">
							<input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
							<input type="submit" class="btn btn-info" value="Модели">
						</form>
					</div>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($client_id && !empty($models)): ?>
			<h3 class="text-light my-3">Модели клиента</h3>
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
				</tr>
				</thead>
				<tbody>
		<?php foreach ($models as $model): ?>
					<tr>
						<td><?php echo htmlspecialchars($model['first_name']); ?></td>
						<td><?php echo htmlspecialchars($model['last_name']); ?></td>
						<td><?php echo htmlspecialchars($genders[$model['gender']]); ?></td>
						<td><?php echo htmlspecialchars($model['birth_date']); ?></td>
						<td><?php echo htmlspecialchars($model['height']); ?> см</td>
						<td><?php echo htmlspecialchars($model['weight']); ?> кг</td>
						<td><?php echo htmlspecialchars($model['hair_color']); ?></td>
						<td><?php echo htmlspecialchars($experience_levels[$model['experience_level']]); ?></td>
						<td><?php echo $model['active_contract'] ? 'Да' : 'Нет'; ?></td>
					</tr>
		<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>
</div>
</body>
</html>
