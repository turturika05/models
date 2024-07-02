<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'];
	$contact_info = $_POST['contact_info'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];

	try {
		$stmt = $pdo->prepare("INSERT INTO clients (name, contact_info, address, phone, email) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$name, $contact_info, $address, $phone, $email]);
		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		echo "Ошибка при добавлении клиента: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить клиента</h3>
	<form action="create_client.php" method="post">
		<div class="form-group">
			<label for="name">Имя:</label>
			<input type="text" id="name" name="name" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="contact_info">Контактная информация:</label>
			<textarea id="contact_info" name="contact_info" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<label for="address">Адрес:</label>
			<input type="text" id="address" name="address" class="form-control">
		</div>
		<div class="form-group">
			<label for="phone">Телефон:</label>
			<input type="text" id="phone" name="phone" class="form-control">
		</div>
		<div class="form-group mb-3">
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" class="form-control">
		</div>
		<button type="submit" class="btn btn-success">Добавить</button>
	</form>
</div>
</body>
</html>
