<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$skill_name = $_POST['skill_name'];

	try {
		$stmt = $pdo->prepare("INSERT INTO skills (skill_name) VALUES (?)");
		$stmt->execute([$skill_name]);
		header('Location: index.php?message=create_success');
	} catch (PDOException $e) {
		echo "Ошибка при добавлении навыка: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Добавить навык</h3>
	<form action="create_skill.php" method="post">
		<div class="form-group my-3">
			<label for="skill_name">Название навыка:</label>
			<input type="text" id="skill_name" name="skill_name" class="form-control" required>
		</div>
		<button type="submit" class="btn btn-success mb-5">Добавить</button>
	</form>
</div>
</body>
</html>
