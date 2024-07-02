<?php
include_once 'header.php';
require_once 'database_connection.php';

// Конфигурация пагинации
$posts_per_page = 5;

// Получение уникальных категорий
try {
	$stmt = $pdo->prepare("SELECT DISTINCT category FROM posts");
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
	echo "Ошибка при получении категорий: " . $e->getMessage();
}

// Получение текущей страницы
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Получение всех новостей с пагинацией
try {
	$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
	$stmt->bindParam(':limit', $posts_per_page, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();
	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Получение общего количества новостей
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM posts");
	$stmt->execute();
	$total_posts = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo "Ошибка при получении новостей: " . $e->getMessage();
}

// Вычисление общего количества страниц
$total_pages = ceil($total_posts / $posts_per_page);
?>

<section class="news-section">
	<h2 class="block-title">Все новости</h2>
	<div class="tabs">
		<span class="tab active" data-tab="all-news">Все новости</span>
	  <?php foreach ($categories as $category): ?>
				<span class="tab" data-tab="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></span>
	  <?php endforeach; ?>
	</div>

	<div id="all-news" class="tab-content active">
		<div class="news-cards-container">
		<?php foreach ($posts as $post): ?>
					<div class="page-news-card">
						<img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
						<h3><?php echo htmlspecialchars($post['title']); ?></h3>
						<p><?php echo htmlspecialchars($post['main_text']); ?></p>
						<a href="news_details.php?post_id=<?php echo $post['post_id']; ?>" class="details-button">Подробнее</a>
					</div>
		<?php endforeach; ?>
		</div>

		<div class="pagination">
		<?php for ($i = 1; $i <= $total_pages; $i++): ?>
					<a class="page-number <?php if ($i == $page) echo 'active'; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
		<?php endfor; ?>
		</div>
	</div>

	<?php foreach ($categories as $category): ?>
		<?php
		// Получение постов для каждой категории с пагинацией
		try {
			$stmt = $pdo->prepare("SELECT * FROM posts WHERE category = :category ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
			$stmt->bindParam(':category', $category, PDO::PARAM_STR);
			$stmt->bindParam(':limit', $posts_per_page, PDO::PARAM_INT);
			$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
			$stmt->execute();
			$category_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Получение общего количества новостей в категории
			$stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE category = :category");
			$stmt->bindParam(':category', $category, PDO::PARAM_STR);
			$stmt->execute();
			$total_category_posts = $stmt->fetchColumn();
		} catch (PDOException $e) {
			echo "Ошибка при получении новостей в категории: " . $e->getMessage();
		}

		// Вычисление общего количества страниц для категории
		$total_category_pages = ceil($total_category_posts / $posts_per_page);
		?>
			<div id="<?php echo htmlspecialchars($category); ?>" class="tab-content">
				<div class="news-cards-container">
			<?php foreach ($category_posts as $post): ?>
							<div class="page-news-card">
								<img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
								<h3><?php echo htmlspecialchars($post['title']); ?></h3>
								<p><?php echo htmlspecialchars($post['main_text']); ?></p>
								<a href="news_details.php?post_id=<?php echo $post['post_id']; ?>" class="details-button">Подробнее</a>
							</div>
			<?php endforeach; ?>
				</div>

				<div class="pagination">
			<?php for ($i = 1; $i <= $total_category_pages; $i++): ?>
							<a class="page-number <?php if ($i == $page) echo 'active'; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
			<?php endfor; ?>
				</div>
			</div>
	<?php endforeach; ?>
</section>

<?php
include_once 'footer.php';
?>

