<?php
include_once 'header.php';
require_once 'database_connection.php';

// Получение ID фотосессии из запроса
$photoshoot_id = isset($_GET['photoshoot_id']) ? (int)$_GET['photoshoot_id'] : 0;

// Проверка валидности ID фотосессии
if ($photoshoot_id <= 0) {
	echo "Неверный ID фотосессии.";
	exit();
}

// Получение данных фотосессии из базы данных
try {
	$stmt = $pdo->prepare("
        SELECT ps.photoshoots_name, ps.description, ps.date
        FROM photoshoots ps
        WHERE ps.id = ?
    ");
	$stmt->execute([$photoshoot_id]);
	$photoshoot = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$photoshoot) {
		echo "Фотосессия не найдена.";
		exit();
	}

	// Получение фотографий для фотосессии
	$stmt = $pdo->prepare("
        SELECT file_path
        FROM photoshoot_photos
        WHERE photoshoot_id = ?
    ");
	$stmt->execute([$photoshoot_id]);
	$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных фотосессии: " . $e->getMessage();
	exit();
}
?>

<section class="photoshoot-details-section">
	<h2 class="block-title"><?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?></h2>
	<div class="details-container">
		<div class="details-info">
			<p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($photoshoot['description'])); ?></p>
			<p><strong>Дата:</strong> <?php echo htmlspecialchars(date('d.m.Y', strtotime($photoshoot['date']))); ?></p>
		</div>
		<div class="details-images">
		<?php foreach ($photos as $photo): ?>
					<div class="details-image-container">
						<img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="Фотография">
					</div>
		<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
include_once 'footer.php';
?>
