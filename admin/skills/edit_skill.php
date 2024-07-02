<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Проверяем, был ли передан skill_id
if (!isset($_GET['skill_id'])) {
	header('Location: index.php');
	exit();
}

$skill_id = $_GET['skill_id'];

// Получение данных навыка
try {
	$stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
	$stmt->execute([$skill_id]);
	$skill = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$skill) {
		echo "Навык не найден.";
		exit();
	}
} catch (PDOException $e) {
	echo "Ошибка при получении данных навыка: " . $e->getMessage();
}

// Обработка запроса на обновление навыка
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$skill_name = $_POST['skill_name'];

	try {
		$stmt = $pdo->prepare("UPDATE skills SET skill_name = ? WHERE id = ?");
		$stmt->execute([$skill_name, $skill_id]);
		header('Location: index.php?message=edit_success');
		exit(); // Важно завершить скрипт после перенаправления
	} catch (PDOException $e) {
		echo "Ошибка при обновлении навыка: " . $e->getMessage();
	}
}
?>

<body class="bg-dark text-light">
<div class="container w-50">
	<h3 class="text-light my-3">Редактировать навык</h3>
	<form action="edit_skill.php?skill_id=<?php echo $skill_id; ?>" method="post">
		<div class="form-group my-3">
			<label for="skill_name">Название навыка:</label>
			<input type="text" id="skill_name" name="skill_name" class="form-control" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" required>
		</div>
		<button type="submit" class="btn btn-success">Сохранить изменения</button>
	</form>
</div>
</body>
</html>
