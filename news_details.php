<?php
include_once 'header.php';
require_once 'database_connection.php';

// Получение ID поста из запроса
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Проверка валидности ID поста
if ($post_id <= 0) {
	echo "Неверный ID поста.";
	exit();
}

// Получение данных поста и автора из базы данных
try {
	$stmt = $pdo->prepare("SELECT p.*, u.full_name FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.post_id = ?");
	$stmt->execute([$post_id]);
	$post = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$post) {
		echo "Пост не найден.";
		exit();
	}
} catch (PDOException $e) {
	echo "Ошибка при получении данных поста: " . $e->getMessage();
	exit();
}
?>

<section class="news-details-section">
	<h2 class="block-title"><?php echo htmlspecialchars($post['title']); ?></h2>
	<div class="details-container">
		<div class="details-image-container">
			<img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="details-image">
		</div>
		<div class="details-info">
			<p><strong>Автор:</strong> <?php echo htmlspecialchars($post['full_name']); ?></p>
			<p><strong>Дата создания:</strong> <?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($post['created_at']))); ?></p>
			<p><strong>Категория:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
		</div>
		<div class="details-text">
			<p><?php echo nl2br(htmlspecialchars($post['main_text'])); ?></p>
		</div>
	</div>
</section>

<?php
include_once 'footer.php';
?>
