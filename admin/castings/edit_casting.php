<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if (!isset($_GET['casting_id'])) {
	header('Location: index.php');
	exit();
}

$casting_id = $_GET['casting_id'];

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$casting_id = $_POST['casting_id'];
	$casting_name = $_POST['casting_name'];
	$description = $_POST['description'];
	$casting_date = $_POST['casting_date'];
	$location = $_POST['location'];
	$client_id = $_POST['client_id'];

	try {
		$stmt = $pdo->prepare("UPDATE castings SET casting_name = ?, description = ?, casting_date = ?, location = ?, client_id = ? WHERE casting_id = ?");
		$stmt->execute([$casting_name, $description, $casting_date, $location, $client_id, $casting_id]);
		header('Location: index.php?message=edit_success');
		exit();
	} catch (PDOException $e) {
		echo "Ошибка при обновлении кастинга: " . $e->getMessage();
	}
}

// Получение данных кастинга и списка клиентов
try {
	$stmt = $pdo->prepare("SELECT * FROM castings WHERE casting_id = ?");
	$stmt->execute([$casting_id]);
	$casting = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$casting) {
		echo "Кастинг не найден.";
		exit();
	}

	$stmt = $pdo->prepare("SELECT id, name FROM clients");
	$stmt->execute();
	$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать кастинг</h3>
	<form action="edit_casting.php?casting_id=<?php echo $casting_id; ?>" method="post">
		<input type="hidden" name="casting_id" value="<?php echo $casting['casting_id']; ?>">
		<div class="form-group">
			<label for="casting_name">Название кастинга:</label>
			<input type="text" id="casting_name" name="casting_name" class="form-control" value="<?php echo htmlspecialchars($casting['casting_name']); ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="description">Описание:</label>
			<textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($casting['description']); ?></textarea>
		</div>
		<div class="form-group my-3">
			<label for="casting_date">Дата кастинга:</label>
			<input type="date" id="casting_date" name="casting_date" class="form-control" value="<?php echo htmlspecialchars($casting['casting_date']); ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="location">Локация:</label>
			<input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($casting['location']); ?>" required>
		</div>
		<div class="form-group my-3">
			<label for="client_id">Клиент:</label>
			<select id="client_id" name="client_id" class="form-control" required>
				<option value="">Выберите клиента</option>
		  <?php foreach ($clients as $client): ?>
						<option value="<?php echo $client['id']; ?>" <?php echo $client['id'] == $casting['client_id'] ? 'selected' : ''; ?>>
				<?php echo htmlspecialchars($client['name']); ?>
						</option>
		  <?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-success">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
