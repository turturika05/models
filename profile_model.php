<?php
include_once 'header.php';
require_once 'database_connection.php';

$model_id = $_GET['model_id'] ?? null;

if (!$model_id) {
	echo "Модель не найдена.";
	exit();
}

try {
	// Получение данных модели
	$stmt = $pdo->prepare("
        SELECT m.*, 
               TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) AS age,
               GROUP_CONCAT(DISTINCT s.skill_name SEPARATOR ', ') AS skills,
               GROUP_CONCAT(DISTINCT mp.file_path SEPARATOR ', ') AS photos
        FROM models m
        LEFT JOIN model_skills ms ON m.id = ms.model_id
        LEFT JOIN skills s ON ms.skill_id = s.id
        LEFT JOIN model_photos mp ON m.id = mp.model_id
        WHERE m.id = :model_id
        GROUP BY m.id
    ");
	$stmt->bindParam(':model_id', $model_id, PDO::PARAM_INT);
	$stmt->execute();
	$model = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$model) {
		echo "Модель не найдена.";
		exit();
	}

} catch (PDOException $e) {
	echo "Ошибка при получении данных модели: " . $e->getMessage();
	exit();
}

try {
	$stmt = $pdo->prepare("
        SELECT ps.id AS photoshoot_id, ps.photoshoots_name, ps.description, ps.date, pp.file_path
        FROM photoshoots ps
        INNER JOIN photoshoot_models pm ON ps.id = pm.photoshoot_id
        INNER JOIN (
            SELECT MIN(id) AS id, photoshoot_id
            FROM photoshoot_photos
            GROUP BY photoshoot_id
        ) pph ON ps.id = pph.photoshoot_id
        INNER JOIN photoshoot_photos pp ON pph.id = pp.id
        WHERE pm.model_id = :model_id
    ");
	$stmt->bindParam(':model_id', $model['id'], PDO::PARAM_INT);
	$stmt->execute();
	$photoshoots = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении фотосессий: " . $e->getMessage();
}

// Массив для преобразования уровня опыта
$experience_levels = [
	'Beginner' => 'Начинающий',
	'Intermediate' => 'Средний',
	'Experienced' => 'Опытный'
];

$experience_level = $model['experience_level'] ?? 'Нет данных';
$experience_level_russian = $experience_levels[$experience_level] ?? 'Нет данных';


?>

<section class="model-profile-section">
	<h2 class="block-title">Профиль
		модели: <?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></h2>
	<div class="profile-container">
		<div class="profile-text">
			<div class="profile-block">
				<h3><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></h3>
			</div>

			<div class="profile-block">
				<table class="profile-table">
					<thead>
					<tr>
						<th>Рост (см)</th>
						<th>Вес (кг)</th>
						<th>Цвет волос</th>
						<th>Уровень опыта</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td><?php echo htmlspecialchars($model['height'] ?? 'Нет данных'); ?></td>
						<td><?php echo htmlspecialchars($model['weight'] ?? 'Нет данных'); ?></td>
						<td><?php echo htmlspecialchars($model['hair_color'] ?? 'Нет данных'); ?></td>
						<td><?php echo htmlspecialchars($experience_level_russian) ?? 'Нет данных'; ?></td>
					</tr>
					</tbody>
				</table>
				<br>
				<div class="profile-block">
					<p>
						Возраст: <?php echo htmlspecialchars($model['age'] ?? 'Нет данных'); ?> лет<br>
						Навыки: <?php echo htmlspecialchars($model['skills'] ?? 'Нет данных'); ?>
					</p>
				</div>
			</div>
		</div>
		<div class="profile-carousel-container">
			<div class="profile-carousel">
		  <?php
		  $photos = explode(', ', $model['photos']);
		  foreach ($photos as $photo): ?>
						<div class="profile-image">
							<img src="<?php echo htmlspecialchars($photo); ?>" alt="Фото модели">
						</div>
		  <?php endforeach; ?>
			</div>
			<div class="carousel-controls">
				<button class="carousel-arrow left-arrow-profile">&larr;</button>
				<button class="carousel-arrow right-arrow-profile">&rarr;</button>
			</div>
		</div>
	</div>
</section>

<section class="current-projects-block">
	<h2 class="block-title">Фотосессии</h2>
	<div class="current-projects-carousel">
		<div class="carousel-wrapper">
			<div class="carousel">
		  <?php foreach ($photoshoots as $photoshoot): ?>
						<div class="project-card">
							<!-- Вывод первого фото из фотосессии -->
							<img src="<?php echo htmlspecialchars($photoshoot['file_path']); ?>"
									 alt="<?php echo htmlspecialchars($photoshoot['photoshoots_name']); ?>">
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


<?php
include_once 'footer.php';
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('.profile-carousel');
        const slides = carousel.querySelectorAll('.profile-image');
        const totalSlides = slides.length;
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, idx) => {
                if (idx === index) {
                    slide.style.display = 'block';
                } else {
                    slide.style.display = 'none';
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        // Показываем первый слайд при загрузке страницы
        showSlide(currentSlide);

        // Управление кнопками слайдера
        const prevButton = document.querySelector('.left-arrow-profile');
        const nextButton = document.querySelector('.right-arrow-profile');

        prevButton.addEventListener('click', prevSlide);
        nextButton.addEventListener('click', nextSlide);
    });
</script>