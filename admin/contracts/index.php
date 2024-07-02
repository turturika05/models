<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка контрактов
try {
	$stmt = $pdo->prepare("
        SELECT contracts.*, models.first_name AS model_first_name, models.last_name AS model_last_name, clients.name AS client_name
        FROM contracts
        JOIN models ON contracts.model_id = models.id
        JOIN clients ON contracts.client_id = clients.id
        WHERE :date BETWEEN contracts.start_date AND contracts.end_date
    ");

	// Устанавливаем параметр :date для фильтрации по дате
	$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
	$stmt->bindParam(':date', $date);

	$stmt->execute();
	$contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка контрактов: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'create_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Контракт успешно добавлен.</div>';
		}

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Контракт успешно обновлен.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Контракт успешно удален.</div>';
		}

		if ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления контракта.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список контрактов</h3>
	<form class="row mb-3 align-items-end" method="GET">
		<div class="col-md-8">
			<div class="form-group mb-0">
				<label for="date" class="form-label">Фильтр по дате:</label>
				<input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
			</div>
		</div>
		<div class="col-md-4">
			<button type="submit" class="btn btn-primary w-100">Применить фильтр</button>
		</div>
	</form>
	<a href="create_contract.php" class="btn btn-success mb-3">Добавить контракт</a>

	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Модель</th>
			<th>Клиент</th>
			<th>Дата начала</th>
			<th>Дата окончания</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($contracts as $contract): ?>
			<tr>
				<td><?php echo $contract['model_first_name'] . ' ' . $contract['model_last_name']; ?></td>
				<td><?php echo $contract['client_name']; ?></td>
				<td><?php echo $contract['start_date']; ?></td>
				<td><?php echo $contract['end_date']; ?></td>
				<td>
					<div class="btn-group" role="group">
						<!-- Форма редактирования контракта -->
						<form method="GET" action="edit_contract.php" class="mx-2">
							<input type="hidden" name="contract_id" value="<?php echo $contract['id']; ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>

						<!-- Форма удаления контракта -->
						<form method="POST" action="delete_contract.php" onsubmit="return confirm('Вы уверены, что хотите удалить этот контракт?');">
							<input type="hidden" name="contract_id" value="<?php echo $contract['id']; ?>">
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
