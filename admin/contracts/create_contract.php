<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка моделей и клиентов для выбора
try {
	$models_stmt = $pdo->prepare("SELECT id, first_name, last_name FROM models");
	$models_stmt->execute();
	$models = $models_stmt->fetchAll(PDO::FETCH_ASSOC);

	$clients_stmt = $pdo->prepare("SELECT id, name FROM clients");
	$clients_stmt->execute();
	$clients = $clients_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$model_id = $_POST['model_id'];
	$client_id = $_POST['client_id'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	try {
		$stmt = $pdo->prepare("INSERT INTO contracts (model_id, client_id, start_date, end_date) VALUES (?, ?, ?, ?)");
		$stmt->execute([$model_id, $client_id, $start_date, $end_date]);
		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		echo "Ошибка при добавлении контракта: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить контракт</h3>
	<form action="create_contract.php" method="post">
		<div class="form-group">
			<label for="model_id">Модель:</label>
			<select id="model_id" name="model_id" class="form-control" required>
				<?php foreach ($models as $model): ?>
					<option value="<?php echo $model['id']; ?>"><?php echo $model['first_name'] . ' ' . $model['last_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="client_id">Клиент:</label>
			<select id="client_id" name="client_id" class="form-control" required>
				<?php foreach ($clients as $client): ?>
					<option value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="start_date">Дата начала:</label>
			<input type="date" id="start_date" name="start_date" class="form-control" required>
		</div>
		<div class="form-group mb-3">
			<label for="end_date">Дата окончания:</label>
			<input type="date" id="end_date" name="end_date" class="form-control" required>
		</div>
		<button type="submit" class="btn btn-success">Добавить</button>
	</form>
</div>
</body>
</html>
