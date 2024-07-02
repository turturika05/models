<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$casting_name = $_POST['casting_name'];
	$description = $_POST['description'];
	$casting_date = $_POST['casting_date'];
	$location = $_POST['location'];
	$client_id = $_POST['client_id'];

	try {
		$stmt = $pdo->prepare("INSERT INTO castings (casting_name, description, casting_date, location, client_id) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$casting_name, $description, $casting_date, $location, $client_id]);
		header('Location: index.php?message=create_success');
		exit();
	} catch (PDOException $e) {
		echo "Ошибка при добавлении кастинга: " . $e->getMessage();
	}
}

// Получение списка клиентов
try {
	$stmt = $pdo->prepare("SELECT id, name FROM clients");
	$stmt->execute();
	$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка клиентов: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить кастинг</h3>
	<form action="create_casting.php" method="post">
		<div class="form-group">
			<label for="casting_name">Название кастинга:</label>
			<input type="text" id="casting_name" name="casting_name" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="description">Описание:</label>
			<textarea id="description" name="description" class="form-control" rows="3"></textarea>
		</div>
		<div class="form-group my-3">
			<label for="casting_date">Дата кастинга:</label>
			<input type="date" id="casting_date" name="casting_date" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="location">Локация:</label>
			<input type="text" id="location" name="location" class="form-control" required>
		</div>
		<div class="form-group my-3">
			<label for="client_id">Клиент:</label>
			<select id="client_id" name="client_id" class="form-control" required>
				<option value="">Выберите клиента</option>
		  <?php foreach ($clients as $client): ?>
						<option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></option>
		  <?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-success">Добавить</button>
	</form>
</div>
</body>
</html>
