<?php
include_once 'header.php';
require_once 'database_connection.php'; // Подключение к базе данных

session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit();
}

$user_id = $_SESSION['user_id'];

// Извлечение данных пользователя из базы данных
$query = $pdo->prepare("SELECT email, phone_number, date_of_birth, full_name, registration_date FROM users WHERE user_id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
	echo "Пользователь не найден.";
	exit();
}

function displayData($data) {
	return empty($data) ? 'Нет данных' : htmlspecialchars($data);
}
?>

<section class="model-profile-section">
	<h2 class="block-title">Профиль пользователя</h2>
	<div class="profile-container">
		<div class="profile-text">
			<div class="profile-block">
				<h3><?php echo displayData($user['full_name']); ?></h3>
			</div>
			<div class="profile-block">
				<table class="profile-table">
					<thead>
					<tr>
						<th>Телефон</th>
						<th>Электронная почта</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td><?php echo displayData($user['phone_number']); ?></td>
						<td><?php echo displayData($user['email']); ?></td>
					</tr>
					</tbody>
				</table>
				<br>
				<div class="profile-block">
					<p>Дата рождения: <?php echo displayData($user['date_of_birth']); ?></p>
					<p>Дата регистрации: <?php echo displayData($user['registration_date']); ?></p>
				</div>
			</div>
		</div>
		<div class="profile-form-container contact-form">
			<h3>Редактировать профиль</h3>
			<form action="update_profile.php" method="post" id="profileForm" onsubmit="return validateForm()">
				<div class="form-group">
					<label for="phone_number">Номер телефона:</label><br>
					<span id="phoneError" class="error"></span>
					<input type="text" id="phone_number" name="phone_number" required>
				</div>
				<div class="form-group">
					<label for="date_of_birth">Дата рождения:</label>
					<input type="date" id="date_of_birth" name="date_of_birth" required>
				</div>
				<div class="form-group">
					<label for="full_name">Полное имя:</label><br>
					<span id="nameError" class="error"></span>
					<input type="text" id="full_name" name="full_name" required>
				</div>
				<div class="form-group">
					<label for="password">Новый пароль:</label>
					<input type="password" id="password" name="password" required>
				</div>
				<button type="submit">Сохранить изменения</button>
			</form>
		</div>
	</div>
</section>

<?php
include_once 'footer.php';
?>
