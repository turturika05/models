<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if (!isset($_GET['client_id'])) {
	header('Location: index.php');
	exit();
}

$client_id = $_GET['client_id'];

// Получение данных клиента
try {
	$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
	$stmt->execute([$client_id]);
	$client = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$client) {
		echo "Клиент не найден.";
		exit();
	}
} catch (PDOException $e) {
	echo "Ошибка при получении данных клиента: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'];
	$contact_info = $_POST['contact_info'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];

	try {
		$stmt = $pdo->prepare("UPDATE clients SET name = ?, contact_info = ?, address = ?, phone = ?, email = ? WHERE id = ?");
		$stmt->execute([$name, $contact_info, $address, $phone, $email, $client_id]);
		header('Location: index.php?message=edit_success');
	} catch (PDOException $e) {
		echo "Ошибка при обновлении клиента: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать клиента</h3>
	<form action="edit_client.php?client_id=<?php echo $client_id; ?>" method="post">
		<div class="form-group">
			<label for="name">Имя:</label>
			<input type="text" id="name" name="name" class="form-control" value="<?php echo $client['name']; ?>" required>
		</div>
		<div class="form-group">
			<label for="contact_info">Контактная информация:</label>
			<textarea id="contact_info" name="contact_info" class="form-control"><?php echo $client['contact_info']; ?></textarea>
		</div>
		<div class="form-group">
			<label for="address">Адрес:</label>
			<input type="text" id="address" name="address" class="form-control" value="<?php echo $client['address']; ?>">
		</div>
		<div class="form-group">
			<label for="phone">Телефон:</label>
			<input type="text" id="phone" name="phone" class="form-control" value="<?php echo $client['phone']; ?>">
		</div>
		<div class="form-group mb-3">
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" class="form-control" value="<?php echo $client['email']; ?>">
		</div>
		<button type="submit" class="btn btn-success">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
