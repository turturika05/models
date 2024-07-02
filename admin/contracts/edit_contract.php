<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if (!isset($_GET['contract_id'])) {
	header('Location: index.php');
	exit();
}

$contract_id = $_GET['contract_id'];

// Получение данных контракта, моделей и клиентов
try {
	$contract_stmt = $pdo->prepare("SELECT * FROM contracts WHERE id = ?");
	$contract_stmt->execute([$contract_id]);
	$contract = $contract_stmt->fetch(PDO::FETCH_ASSOC);

	if (!$contract) {
		echo "Контракт не найден.";
		exit();
	}

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
		$stmt = $pdo->prepare("UPDATE contracts SET model_id = ?, client_id = ?, start_date = ?, end_date = ? WHERE id = ?");
		$stmt->execute([$model_id, $client_id, $start_date, $end_date, $contract_id]);
		header('Location: index.php?message=edit_success');
	} catch (PDOException $e) {
		echo "Ошибка при обновлении контракта: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать контракт</h3>
	<form action="edit_contract.php?contract_id=<?php echo $contract_id; ?>" method="post">
		<div class="form-group">
			<label for="model_id">Модель:</label>
			<select id="model_id" name="model_id" class="form-control" required>
				<?php foreach ($models as $model): ?>
					<option value="<?php echo $model['id']; ?>" <?php if ($model['id'] == $contract['model_id']) echo 'selected'; ?>>
						<?php echo $model['first_name'] . ' ' . $model['last_name']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="client_id">Клиент:</label>
			<select id="client_id" name="client_id" class="form-control" required>
				<?php foreach ($clients as $client): ?>
					<option value="<?php echo $client['id']; ?>" <?php if ($client['id'] == $contract['client_id']) echo 'selected'; ?>>
						<?php echo $client['name']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="start_date">Дата начала:</label>
			<input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $contract['start_date']; ?>" required>
		</div>
		<div class="form-group mb-3">
			<label for="end_date">Дата окончания:</label>
			<input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $contract['end_date']; ?>" required>
		</div>
		<button type="submit" class="btn btn-success">Сохранить</button>
	</form>
</div>
</body>
</html>
