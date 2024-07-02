<?php
include_once 'header.php';
require_once 'database_connection.php';

// Конфигурация пагинации
$models_per_page = 4;

// Получение уникальных категорий (experience_level)
try {
	$stmt = $pdo->prepare("SELECT DISTINCT experience_level FROM models");
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
	echo "Ошибка при получении категорий: " . $e->getMessage();
}

$experience_levels = [
	'Beginner' => 'Начинающие',
	'Intermediate' => 'Средние',
	'Experienced' => 'Опытные'
];

// Получение текущей страницы
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $models_per_page;

// Массив для хранения данных моделей
$modelCards = array();

// Получение моделей с фотографиями для каждой категории
foreach ($categories as $category) {
	try {
		// Выбираем модели для текущей категории с их первой фотографией
		$stmt = $pdo->prepare("
            SELECT m.*, mp.file_path, TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) AS age
            FROM models m
            LEFT JOIN (
                SELECT model_id, MIN(file_path) as file_path
                FROM model_photos
                GROUP BY model_id
            ) mp ON m.id = mp.model_id
            WHERE m.experience_level = :category
            ORDER BY m.id
            LIMIT :limit OFFSET :offset
        ");
		$stmt->bindParam(':category', $category, PDO::PARAM_STR);
		$stmt->bindParam(':limit', $models_per_page, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		$models = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$modelCards[$category] = $models;
	} catch (PDOException $e) {
		echo "Ошибка при получении моделей: " . $e->getMessage();
	}
}

// Вычисление общего количества моделей
try {
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM models");
	$stmt->execute();
	$total_models = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo "Ошибка при получении общего количества моделей: " . $e->getMessage();
}

// Вычисление общего количества страниц
$total_pages = ceil($total_models / $models_per_page);
?>

<section class="portfolio-section">
	<h2 class="block-title">Портфолио моделей</h2>

	<div class="tabs">
		<span class="tab active" data-category="all">Все модели</span>
	  <?php foreach ($categories as $index => $category): ?>
				<span class="tab <?php echo ($index === 0) ? 'active' : ''; ?>" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($experience_levels[$category]); ?></span>
	  <?php endforeach; ?>
	</div>

	<div class="model-cards-container">
	  <?php foreach ($categories as $category): ?>
		  <?php foreach ($modelCards[$category] as $model): ?>
					<div class="model-card" data-category="<?php echo htmlspecialchars($category); ?>">
						<div class="model-image">
							<img src="<?php echo htmlspecialchars($model['file_path']); ?>" alt="Model Image">
							<div class="model-info">
								<div class="model-name"><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></div>
							</div>
							<div class="model-price"><?php echo $model['age']; ?> лет</div>
						</div>
						<a href="profile_model.php?model_id=<?php echo $model['id']; ?>" class="details-button">Подробнее</a>
					</div>
		  <?php endforeach; ?>
	  <?php endforeach; ?>
	</div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab');
        const modelCards = document.querySelectorAll('.model-card');

        // Убрать класс 'active' у всех вкладок при загрузке
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });

        tabs[0].classList.add('active');

        // Показать все модели при загрузке страницы и применить класс 'active' ко всем моделям
        modelCards.forEach(card => {
            card.style.display = 'block';
            card.classList.add('active');
        });

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const category = tab.getAttribute('data-category');

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                modelCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>




<?php
include_once 'footer.php';
?>
