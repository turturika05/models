<?php
include_once 'header.php';
require_once 'database_connection.php';

$currentDate = date('Y-m-d');
try {
	// Запрос на текущие проекты
	$stmt = $pdo->prepare("
        SELECT ps.id AS photoshoot_id, ps.photoshoots_name, ps.description, ps.date, pp.file_path
        FROM photoshoots ps
        LEFT JOIN (
            SELECT photoshoot_id, MIN(file_path) AS file_path
            FROM photoshoot_photos
            GROUP BY photoshoot_id
        ) pp ON ps.id = pp.photoshoot_id
        WHERE ps.date >= CURDATE()
        ORDER BY ps.date ASC
    ");
	$stmt->execute();
	$currentProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Запрос на завершенные проекты
	$stmt = $pdo->prepare("
        SELECT ps.id AS photoshoot_id, ps.photoshoots_name, ps.description, ps.date, pp.file_path
        FROM photoshoots ps
        LEFT JOIN (
            SELECT photoshoot_id, MIN(file_path) AS file_path
            FROM photoshoot_photos
            GROUP BY photoshoot_id
        ) pp ON ps.id = pp.photoshoot_id
        WHERE ps.date < CURDATE()
        ORDER BY ps.date DESC
    ");
	$stmt->execute();
	$completedProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении фотосессий: " . $e->getMessage();
}

?>

	<section class="current-projects-block">
		<h2 class="block-title">Проекты</h2>
		<h3>Текущие проекты</h3>
		<div class="current-projects-carousel">
			<div class="carousel-wrapper">
				<div class="carousel">
			<?php foreach ($currentProjects as $photoshoot): ?>
							<div class="project-card">
								<img src="<?php echo htmlspecialchars($photoshoot['file_path']); ?>" alt="<?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?>">
								<h3><?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?></h3>
								<p><?php echo htmlspecialchars($photoshoot['description']); ?></p>
								<a href="project_details.php?photoshoot_id=<?php echo $photoshoot['photoshoot_id']; ?>" class="details-button">Подробнее</a>
							</div>
			<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="carousel-controls">
			<button class="carousel-arrow left-arrow">&larr;</button>
			<button class="carousel-arrow right-arrow">&rarr;</button>
		</div>
	</section>

	<section class="completed-projects-block">
		<h3>Завершенные проекты</h3>
		<div class="completed-projects-carousel">
			<div class="carousel-wrapper">
				<div class="carousel">
			<?php foreach ($completedProjects as $photoshoot): ?>
							<div class="project-card">
								<img src="<?php echo htmlspecialchars($photoshoot['file_path']); ?>" alt="<?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?>">
								<h3><?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?></h3>
								<p><?php echo htmlspecialchars($photoshoot['description']); ?></p>
								<a href="project_details.php?photoshoot_id=<?php echo $photoshoot['photoshoot_id']; ?>" class="details-button">Подробнее</a>
							</div>
			<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="carousel-controls">
			<button class="carousel-arrow left-arrow">&larr;</button>
			<button class="carousel-arrow right-arrow">&rarr;</button>
		</div>
	</section>

<?php include_once 'footer.php'; ?>