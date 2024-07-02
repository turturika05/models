<?php
include_once 'header.php';
require_once 'database_connection.php';

try {
	$stmt = $pdo->prepare("
        SELECT castings.*, clients.name 
        FROM castings 
        LEFT JOIN clients ON castings.client_id = clients.id
    ");
	$stmt->execute();
	$castings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении данных кастингов: " . $e->getMessage();
}
?>

<section class="casting-section">
		<?
	if (isset($_SESSION['success_message'])) {
		echo "<div class='success-message'>{$_SESSION['success_message']}</div>";
		unset($_SESSION['success_message']);
	}

	if (isset($_SESSION['error_message'])) {
		echo "<div class='error-message'>{$_SESSION['error_message']}</div>";
		unset($_SESSION['error_message']);
	}

	try {
		$stmt = $pdo->prepare("
        SELECT castings.*, clients.name 
        FROM castings 
        LEFT JOIN clients ON castings.client_id = clients.id
    ");
		$stmt->execute();
		$castings = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		echo "Ошибка при получении данных кастингов: " . $e->getMessage();
	}
		?>
	<h2 class="block-title">Кастинги</h2>
	<div class="casting-cards-container">
	  <?php foreach ($castings as $casting): ?>
				<div class="casting-card">
					<h3><?php echo htmlspecialchars($casting['casting_name']); ?></h3>
					<p><?php echo nl2br(htmlspecialchars($casting['description'])); ?></p>
					<p>Дата и время: <?php echo htmlspecialchars($casting['casting_date']); ?></p>
					<p>Местоположение: <?php echo htmlspecialchars($casting['location']); ?></p>
					<p>Клиент: <?php echo htmlspecialchars($casting['name']); ?></p>
			<?php if (isset($_SESSION['user_id'])): ?>
							<button class="apply-button" data-casting-id="<?php echo $casting['casting_id']; ?>">Подать заявку</button>
			<?php else: ?>
				<button class="apply-button" data-casting-id="<?php echo $casting['casting_id']; ?>">Подать заявку</button>

	  <?php endif; ?>
				</div>
	  <?php endforeach; ?>
	</div>
</section>

<div id="registrationModal" class="modal">
	<div class="modal-content">
		<div class="modal-container">
			<div class="modal-image">
				<img src="img/form_image.png" alt="Регистрационное изображение">
			</div>
			<div class="modal-form">
				<span class="close">&times;</span>
				<h2>Регистрационная форма участника</h2>
				<form id="castingApplicationForm" action="submit_application.php" method="post">
					<label for="name">Имя*</label>
					<input type="text" id="name" name="name" placeholder="Введите имя" required>

					<label for="phone">Телефон</label>
					<input type="tel" id="phone" name="phone" placeholder="Введите номер телефона" required>

					<label for="email">E-mail*</label>
					<input type="email" id="email" name="email" placeholder="Введите E-mail" required>

					<label for="casting">Кастинг</label>
					<input type="text" id="casting" name="casting" readonly required>

					<label for="message">Сообщение</label>
					<textarea id="message" name="message" rows="3" placeholder="Напишите что-нибудь (максимум 100 символов)" required></textarea>

					<input type="hidden" id="casting_id" name="casting_id">
					<button type="submit">Подать заявку</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const modal = document.getElementById("registrationModal");
        const closeBtn = document.querySelector(".modal .close");
        const castingForm = document.getElementById("castingApplicationForm");

        document.querySelectorAll(".apply-button").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const castingId = button.getAttribute("data-casting-id");
                const castingName = button.parentNode.querySelector("h3").textContent;

                document.getElementById("casting").value = castingName;
                document.getElementById("casting_id").value = castingId; // Устанавливаем casting_id в скрытое поле

                // Если пользователь авторизован, сразу отправляем данные
				<?php if (isset($_SESSION['user_id'])): ?>
                submitApplication();
				<?php else: ?>
                modal.style.display = "block"; // Открываем модальное окно для неавторизованного пользователя
				<?php endif; ?>
            });
        });

        closeBtn.onclick = () => {
            modal.style.display = "none";
        };

        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        // Функция для отправки заявки
        function submitApplication() {
            castingForm.submit();
        }
    });

</script>

<?php
include_once 'footer.php';
?>
