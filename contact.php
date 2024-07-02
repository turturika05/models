<?php
include_once 'header.php';
require_once 'database_connection.php'; // Подключение к базе данных
require 'mail_config.php'; // Подключение конфигурации почты

header('Content-Type: text/html; charset=utf-8');

session_start();

// Обработка формы обратной связи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	// Отправка письма админу
	try {
		$mail = setupMailer();
		$mail->addAddress('turturika05@mail.ru'); // Почта администратора

		// Установка темы и тела письма
		$mail->isHTML(true);
		$mail->Subject = 'Новое сообщение от пользователя';
		$mail->Body = "
            Имя: $name<br>
            Телефон: $phone<br>
            Email: $email<br>
            Тема: $subject<br>
            Сообщение: $message
        ";

		$mail->send();
		$feedback = 'Ваше сообщение успешно отправлено.';
	} catch (Exception $e) {
		$feedback = 'Ошибка отправки сообщения: ' . $e->getMessage();
	}
}
?>

<section class="contact-info">
	<div class="feature-block-header">
		<h2>Контактная информация</h2>
	</div>
	<div class="contact-container">
		<div class="contact-details">
			<h3>Адрес:</h3>
			<ul>
				<li><p>119019, г. Москва, ул. Арбат, д.1</p></li>
			</ul>
			<h3>Телефоны:</h3>
			<ul>
				<li><p>Основной: +7 (777) 123-45-67</p></li>
				<li><p>Дополнительный: +7 (777) 123-45-67</p></li>
				<li><p>Факс: +7 (777) 123-45-67</p></li>
				</ul>
				<h3>Электронная почта:</h3>
				<ul>
					<li><p>Общие вопросы: <a href="mailto:info@modelspro.ru">info@modelspro.ru</a></p></li>
					<li><p>Отдел продаж: <a href="mailto:sales@modelspro.ru">sales@modelspro.ru</a></p></li>
					<li><p>Техническая поддержка: <a href="mailto:support@modelspro.ru">support@modelspro.ru</a></p></li>
				</ul>
		</div>
		<div class="contact-map">
			<img src="img/map.png" alt="Карта">
		</div>
	</div>
</section>

<section class="contact-form-section">
	<div class="contact-form-container">
		<div class="contact-image">
			<img src="img/image_contact.png" alt="Контактное изображение">
		</div>
		<div class="contact-form">
			<h2>Будем рады ответить на ваши <br> вопросы и помочь вам</h2>
		<?php if (isset($feedback)): ?>
					<p style="color: green;"><?php echo $feedback; ?></p>
		<?php endif; ?>
			<form method="post" id="contactForm">
				<input type="text" id="name" name="name" placeholder="Ваше имя" required>
				<input type="tel" id="phone" name="phone" placeholder="Введите номер телефона" required>
				<input type="email" id="email" name="email" placeholder="Введите e-mail" required>
				<input type="text" id="subject" name="subject" placeholder="Тема сообщения" required>
				<textarea id="message" name="message" rows="5" placeholder="Текст сообщения" required></textarea>
				<button type="submit" name="contact_form">Отправить</button>
			</form>
		</div>
	</div>
</section>

<?php
include_once 'footer.php';
?>
