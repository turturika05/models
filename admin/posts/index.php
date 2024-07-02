<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка постов с именами авторов
try {
	$stmt = $pdo->prepare("
        SELECT p.post_id, p.title, p.category, p.created_at, u.full_name 
        FROM posts p 
        JOIN users u ON p.user_id = u.user_id 
        ORDER BY p.created_at DESC
    ");
	$stmt->execute();
	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка постов: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'create_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Пост успешно добавлен.</div>';
		}

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Пост успешно обновлен.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Пост успешно удален.</div>';
		}

		if ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления поста.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список новостей</h3>
	<a href="create_post.php" class="btn btn-success mb-3">Добавить новость</a>
	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Заголовок</th>
			<th>Категория</th>
			<th>Автор</th>
			<th>Дата создания</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($posts as $post): ?>
			<tr>
				<td><?php echo $post['title']; ?></td>
				<td><?php echo $post['category']; ?></td>
				<td><?php echo $post['full_name']; ?></td>
				<td><?php echo $post['created_at']; ?></td>
				<td>
					<div class="btn-group" role="group">
						<!-- Форма редактирования поста -->
						<form method="GET" action="edit_post.php" class="mx-2">
							<input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>

						<!-- Форма удаления поста -->
						<form method="POST" action="delete_post.php" onsubmit="return confirm('Вы уверены, что хотите удалить этот пост?');">
							<input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
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
