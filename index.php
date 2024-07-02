<?php
include_once 'header.php';

require_once 'database_connection.php'; // Подключение к базе данных

try {
    // Подготовка SQL запроса для получения всех постов (новостей)
    $stmt = $pdo->query("
        SELECT *
        FROM posts
        ORDER BY created_at DESC  -- Сортировка по дате создания, чтобы новые посты были первыми
        LIMIT 6  -- Ограничение на количество новостей
    ");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка при получении новостей: " . $e->getMessage();
}
?>


<section class="content-block">
	<div class="text-block">
		<h1>Мы рады приветствовать <br> вас на официальной <br> странице Models pro</h1>
		<p>Наша миссия - открывать новые таланты и предоставлять им уникальные возможности для успешной карьеры в модельном
			бизнесе.</p>
		<div>
			<a href="casting.php" class="action-button">Кастинги</a>
			<a href="portfolio.php" class="more-link">Портфолио моделей</a>
		</div>
	</div>
	<div class="image-block">
		<img src="img/sec_image.png" alt="Изображение">
	</div>
</section>

<section class="feature-block">
	<div class="feature-block-inner">
		<div class="feature-block-header">
			<h2>Чем мы занимаемся ?</h2>
		</div>
		<p>MODELS PRO - это команда профессионалов с многолетним опытом <br> работы в индустрии моды и красоты.</p>

		<div class="card-container">
			<div class="card">
				<img src="img/icon1.png" alt="Иконка карточки">
				<h3>Подбор и развитие моделей</h3>
				<p>Мы находим и развиваем новых талантов</p>
			</div>
			<div class="card">
				<img src="img/icon2.png" alt="Иконка карточки">
				<h3>Организация мероприятий</h3>
				<p>Наше агентство проводит модные показы, фотосессии и рекламные кампании, создавая платформы для моделей,
					брендов для демонстрации своих возможностей.</p>
			</div>
			<div class="card">
				<img src="img/icon3.png" alt="Иконка карточки">
				<h3>Международное продвижение</h3>
				<p>Мы сотрудничаем с ведущими модными домами и агентствами</p>
			</div>
		</div>
	</div>
</section>


<section class="image-text-block">
	<div class="image-text-block-inner">
		<h2 class="block-title">Наша история</h2>
		<div class="content-wrapper">
			<div class="text-column">
				<p>MODELS PRO было основано Дарьей Ивановой в Москве с целью открывать и развивать новые таланты в мире моды. С
					самого начала агентство привлекло внимание крупных брендов и дизайнеров, организовывая успешные кастинги и
					фотосессии.
				</p>
				<p>
					Быстро расширяясь, агентство открыло филиалы в Париже, Нью-Йорке и Милане. Запуск онлайн-платформы упростил
					процесс поиска и бронирования моделей, что укрепило позиции MODELS PRO на международной арене.
				</p>
				<p> Сегодня MODELS PRO - одно из ведущих модельных агентств мира, известное своим профессионализмом и высоким
					уровнем сервиса. Мы продолжаем развивать новые таланты и поддерживать модели на пути к международному
					успеху.
				</p>
			</div>
			<div class="image-column">
				<div class="image-overlay">
					<img src="img/image_2.png" alt="Изображение 1">
					<img src="img/image_1.png" alt="Изображение 2">
				</div>
			</div>
		</div>
	</div>
</section>

<section class="image-links-block">
	<div class="image-link">
		<a href="portfolio.php">
			<img src="img/image_link_1.png" alt="Наши модели">
			<p>Наши модели</p>
		</a>
	</div>
	<div class="image-link">
		<a href="casting.php">
			<img src="img/image_link_2.png" alt="Кастинги">
			<p>Кастинги</p>
		</a>
	</div>
</section>

<section class="news-block">
	<h2 class="block-title">Новости</h2>
	<div class="news-carousel">
		<div class="carousel-wrapper">
			<div class="carousel">
		  <?php foreach ($posts as $post): ?>
						<div class="news-card">
							<img src="<?php echo $post['image_path']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
							<a href="news_details.php?post_id=<?php echo $post['post_id']; ?>"><h3><?php echo htmlspecialchars($post['title']); ?></h3></a>
							<p><?php echo htmlspecialchars($post['main_text']); ?></p>
						</div>
		  <?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="carousel-controls">
		<button class="carousel-arrow left-arrow">&larr;</button>
		<button class="carousel-arrow right-arrow">&rarr;</button>
	</div>
	<button class="all-news-button">Все новости</button>
</section>

<section class="reviews-block">
	<h2 class="block-title">Отзывы с наших показов</h2>
	<p class="block-description">
		Наши модели участвуют в различных показах и вызывают <br> ажиотаж в сфере моды своими способностями
	</p>
	<div class="reviews-carousel">
		<div class="carousel-wrapper">
			<div class="review-carousel">
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Jennifer Black</p>
				</div>
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Phillip Colligan</p>
				</div>
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Leslie Carrillo</p>
				</div>
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Jennifer Black</p>
				</div>
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Leslie Carrillo</p>
				</div>
				<div class="review-card">
					<div class="stars">
						&#9733;&#9733;&#9733;&#9733;&#9733;
					</div>
					<p class="review-text">Wow, that was just awesome. The professionalism of the models is off the scale. And
						these outfits....I want to join the ranks of the agency</p>
					<p class="reviewer-name">Jennifer Black</p>
				</div>
			</div>
		</div>
	</div>
	<div class="carousel-controls">
		<button class="carousel-arrow review-left-arrow">&larr;</button>
		<button class="carousel-arrow review-right-arrow">&rarr;</button>
	</div>
</section>

<?php
include_once 'footer.php';
?>
